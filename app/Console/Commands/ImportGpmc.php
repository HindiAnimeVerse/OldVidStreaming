<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportGpmc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gpmc:import {user_id} {auth_file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Google Photos for a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $authFile = $this->argument('auth_file');

        // Read auth data from file
        if (!file_exists($authFile)) {
            $this->error("Auth file not found: $authFile");
            return 1;
        }
        $authData = file_get_contents($authFile);

        // Clean up auth file immediately for security
        @unlink($authFile);

        try {
            $user = User::findOrFail($userId);
        } catch (\Exception $e) {
            $this->error("User not found: $userId");
            return 1;
        }

        $progressFile = storage_path('app/public/gpmc_import_progress_' . $userId . '.json');

        // Initial progress write (if not handled by python script immediately)
        file_put_contents($progressFile, json_encode(['processed' => 0, 'status' => 'processing']));

        Log::info("Starting GPMC import for user: $userId");

        try {
            // Using python (assuming it's in path, otherwise use full path or local python)
            // Windows: python usually. Laravel Laragon: python.
            $python = 'python';
            $scriptPath = base_path('api/gpmc_wrapper.py');

            $command = "$python \"$scriptPath\" --auth_data " . escapeshellarg($authData) . " --import_all --progress_file " . escapeshellarg($progressFile);

            // Execute synchronously here since the Artisan command itself is run in background
            $output = shell_exec($command);
            $result = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid JSON output from python script: " . substr($output ?? '', 0, 200));
            }

            if (!is_array($result) || !isset($result['success'])) {
                throw new \Exception("Unexpected response format from python script");
            }

            if (!$result['success']) {
                throw new \Exception($result['message'] ?? 'Unknown error from python script');
            }

            $files = $result['files'] ?? [];

            DB::beginTransaction();
            foreach ($files as $file) {
                DB::table('files')->updateOrInsert(
                    ['dedup_id' => $file['dedup_id']],
                    [
                        'user_id' => $userId,
                        'filename' => $file['filename'] ?? 'unknown',
                        'file_size' => $file['file_size'] ?? 0,
                        'media_id' => $file['media_id'] ?? null,
                        'timestamp' => $file['timestamp'] ?? now(),
                        'media_url' => $file['media_url'] ?? '',
                        'resolution' => $file['resolution'] ?? null,
                        'rip_type' => 'WEB-DL',
                        'codec' => 'h264',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
            DB::commit();

            // Mark as completed
            file_put_contents($progressFile, json_encode([
                'processed' => count($files),
                'status' => 'completed',
                'count' => count($files),
                'success' => true,
                'message' => 'Import successfully completed!'
            ]));

            Log::info("GPMC import completed for user $userId. Imported " . count($files) . " files.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("GPMC Import Error for user $userId: " . $e->getMessage());
            file_put_contents($progressFile, json_encode([
                'error' => $e->getMessage(),
                'status' => 'failed',
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ]));
            return 1;
        }

        return 0;
    }
}
