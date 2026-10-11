<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's files
        $userFiles = DB::table('files')->where('user_id', $user->id);
        $totalFiles = $userFiles->count();
        $totalSize = $userFiles->sum('file_size');

        // Get real stats from database
        $stats = [
            'total_files' => $totalFiles,
            'storage_used' => \App\Helpers\FormatHelper::formatBytes($totalSize),
            'dmca_notices' => 0,
            'bandwidth_used' => '0 GB', // Can be implemented later with tracking
        ];

        // Get user's recent files
        $recentFilesQuery = DB::table('files')
            ->where('user_id', $user->id)
            ->orderBy('timestamp', 'desc')
            ->limit(10)
            ->get();

        $recentFiles = $recentFilesQuery->map(function ($file) {
            return [
                'name' => $file->filename,
                'size' => \App\Helpers\FormatHelper::formatBytes($file->file_size),
                'date' => \Carbon\Carbon::createFromTimestamp($file->timestamp)->diffForHumans(),
            ];
        });

        // Storage breakdown by type (using resolution as indicator for videos)
        $videoSize = DB::table('files')
            ->where('user_id', $user->id)
            ->whereNotNull('resolution')
            ->sum('file_size');
        $imageSize = DB::table('files')
            ->where('user_id', $user->id)
            ->whereNull('resolution')
            ->sum('file_size');

        $storage = [
            'videos' => \App\Helpers\FormatHelper::formatBytes($videoSize),
            'images' => \App\Helpers\FormatHelper::formatBytes($imageSize),
            'total' => \App\Helpers\FormatHelper::formatBytes($totalSize),
        ];

        return view('dashboard.index', compact('user', 'stats', 'recentFiles', 'storage'));
    }

    /**
     * Show user's files page
     */
    public function files(Request $request)
    {
        $user = Auth::user();

        $query = DB::table('files')->where('user_id', $user->id);

        // Search
        if ($request->filled('search')) {
            $query->where('filename', 'like', '%' . $request->search . '%');
        }

        // Filter by type
        if ($request->filled('type')) {
            if ($request->type === 'video') {
                $query->whereNotNull('resolution');
            } elseif ($request->type === 'image') {
                $query->whereNull('resolution');
            }
        }

        // Sort
        $sortField = $request->get('sort', 'timestamp');
        $sortDir = $request->get('dir', 'desc');
        $allowedSorts = ['filename', 'file_size', 'timestamp'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $files = $query->paginate(24)->withQueryString();

        // Format files for view
        $files->getCollection()->transform(function ($file) {
            $file->formatted_size = \App\Helpers\FormatHelper::formatBytes($file->file_size);
            $file->formatted_date = \Carbon\Carbon::createFromTimestamp($file->timestamp)->diffForHumans();
            $file->is_video = !empty($file->resolution);
            return $file;
        });

        // Stats
        $totalFiles = DB::table('files')->where('user_id', $user->id)->count();
        $totalSize = DB::table('files')->where('user_id', $user->id)->sum('file_size');

        return view('dashboard.files', compact('user', 'files', 'totalFiles', 'totalSize'));
    }

    /**
     * Show settings page
     */
    public function settings()
    {
        $user = Auth::user();
        return view('dashboard.settings', compact('user'));
    }

    /**
     * Show player settings page
     */
    public function playerSettings()
    {
        $user = Auth::user();
        $settings = $user->player_settings ?? ['netflix_skin' => true];
        return view('dashboard.player-settings', compact('user', 'settings'));
    }

    /**
     * Update player settings
     */
    public function updatePlayerSettings(Request $request)
    {
        $user = Auth::user();

        $settings = $user->player_settings ?? [];

        // Update settings dynamically based on request
        $allowedKeys = ['netflix_skin', 'share_button', 'playback_rate'];
        foreach ($allowedKeys as $key) {
            if ($request->has($key)) {
                $settings[$key] = $request->boolean($key);
            }
        }

        $user->update(['player_settings' => $settings]);

        return response()->json([
            'success' => true,
            'message' => 'Player settings updated',
            'settings' => $settings
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }

    /**
     * Generate or regenerate API key for the user
     */
    public function generateApiKey()
    {
        $user = Auth::user();

        // Generate a secure random API key
        $apiKey = bin2hex(random_bytes(32)); // 64 character hex string

        // Update user's API key
        DB::table('users')->where('id', $user->id)->update(['api_key' => $apiKey]);

        return response()->json([
            'success' => true,
            'api_key' => $apiKey,
            'message' => 'API key generated successfully'
        ]);
    }

    /**
     * Verify GPMC Connection
     */
    public function verifyGpmc(Request $request)
    {
        $user = Auth::user();
        if (!$user->can_use_gpmc && !$user->is_admin) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to use this feature.']);
        }

        $authData = $request->input('auth_data');
        if (!$authData) {
            return response()->json(['success' => false, 'message' => 'Auth Data required']);
        }

        $scriptPath = base_path('api/gpmc_wrapper.py');

        // Try python3.14 first (Linux/CyberPanel), then python (Windows)
        $pythonCmd = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'python' : 'python3.14';
        $command = "{$pythonCmd} \"{$scriptPath}\" --verify --auth_data \"" . addslashes($authData) . "\" 2>&1";

        try {
            $output = shell_exec($command);
            $result = json_decode($output, true);

            if ($result && $result['success']) {
                $user->gpmc_auth_data = $authData;
                $user->save();
                return response()->json(['success' => true, 'message' => 'Connection successful!']);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Unknown error from script',
                'raw_output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Import GPMC Items - Clean implementation with database progress
     */
    public function importGpmc(Request $request)
    {
        $user = Auth::user();
        if (!$user->can_use_gpmc && !$user->is_admin) {
            return response()->json(['success' => false, 'message' => 'Permission denied']);
        }

        $authData = $user->gpmc_auth_data;
        if (!$authData) {
            return response()->json(['success' => false, 'message' => 'Auth Data not found. Please connect first.']);
        }

        // Set initial progress in database
        $user->gpmc_import_progress = ['status' => 'running', 'processed' => 0, 'message' => 'Starting import...'];
        $user->save();

        // Increase time limit for large imports
        set_time_limit(600);

        try {
            // Run Python script synchronously
            $scriptPath = base_path('api/gpmc_wrapper.py');

            // Generate callback URL and secret for progress updates
            // Use request base URL in case APP_URL is not configured correctly
            $baseUrl = $request->getSchemeAndHttpHost() . $request->getBaseUrl();
            $callbackUrl = $baseUrl . '/api/internal/progress-callback';
            $callbackSecret = substr(md5(config('app.key') . $user->id), 0, 16);

            $descriptors = [
                0 => ['pipe', 'r'],  // stdin
                1 => ['pipe', 'w'],  // stdout
                2 => ['pipe', 'w'],  // stderr
            ];

            // Use python3.14 on Linux/CyberPanel, python on Windows
            $pythonCmd = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'python' : 'python3.14';
            $process = proc_open(
                "{$pythonCmd} \"{$scriptPath}\" --import_all --auth_stdin --callback_url \"{$callbackUrl}\" --user_id {$user->id} --callback_secret \"{$callbackSecret}\"",
                $descriptors,
                $pipes
            );

            if (!is_resource($process)) {
                throw new \Exception("Failed to start Python process");
            }

            // Write auth_data to stdin
            fwrite($pipes[0], $authData);
            fclose($pipes[0]);

            // Read output
            $output = stream_get_contents($pipes[1]);
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            $exitCode = proc_close($process);

            if ($exitCode !== 0) {
                throw new \Exception("Python script failed: " . ($stderr ?: $output));
            }

            $result = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE || !$result) {
                throw new \Exception("Invalid response from Python script");
            }

            if (!$result['success']) {
                throw new \Exception($result['message'] ?? 'Import failed');
            }

            $files = $result['files'] ?? [];
            $count = 0;

            // Save files to database with progress updates
            DB::beginTransaction();
            try {
                foreach ($files as $file) {
                    // Generate unique slug for new records
                    do {
                        $slug = \Illuminate\Support\Str::random(12);
                    } while (DB::table('files')->where('slug', $slug)->exists());

                    DB::table('files')->updateOrInsert(
                        ['dedup_id' => $file['dedup_id']],
                        [
                            'user_id' => $user->id,
                            'filename' => $file['filename'] ?? 'unknown',
                            'slug' => $slug,
                            'file_size' => $file['file_size'] ?? 0,
                            'media_id' => $file['media_id'] ?? null,
                            'timestamp' => $file['timestamp'] ?? now(),
                            'media_url' => $file['media_url'] ?? '',
                            'resolution' => $file['resolution'] ?? null,
                            'rip_type' => $file['rip_type'] ?? null,
                            'codec' => $file['codec'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                    $count++;

                    // Update progress every 50 items
                    if ($count % 50 === 0) {
                        DB::table('users')->where('id', $user->id)->update([
                            'gpmc_import_progress' => json_encode([
                                'status' => 'running',
                                'processed' => $count,
                                'message' => "Saving files... ($count items)"
                            ])
                        ]);
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            // Mark as completed and save state_token for incremental sync
            $stateToken = $result['state_token'] ?? '';
            DB::table('users')->where('id', $user->id)->update([
                'gpmc_import_progress' => json_encode([
                    'status' => 'completed',
                    'processed' => $count,
                    'message' => "Successfully imported $count items!"
                ]),
                'gpmc_state_token' => $stateToken
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully imported $count items!",
                'count' => $count
            ]);
        } catch (\Exception $e) {
            // Mark as failed
            DB::table('users')->where('id', $user->id)->update([
                'gpmc_import_progress' => json_encode([
                    'status' => 'failed',
                    'processed' => 0,
                    'message' => 'Import failed: ' . $e->getMessage()
                ])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check Import Progress from Database
     */
    public function checkGpmcProgress()
    {
        $user = Auth::user();
        $progress = $user->gpmc_import_progress;

        if ($progress) {
            return response()->json($progress);
        }

        return response()->json(['status' => 'idle', 'processed' => 0, 'message' => '']);
    }

    /**
     * Progress callback from Python script (internal API)
     */
    public function updateProgressCallback(Request $request)
    {
        $userId = $request->input('user_id');
        $processed = $request->input('processed', 0);
        $status = $request->input('status', 'running');
        $message = $request->input('message', '');
        $secret = $request->input('secret');

        // Simple secret validation (use app key hash for security)
        $expectedSecret = substr(md5(config('app.key') . $userId), 0, 16);
        if ($secret !== $expectedSecret) {
            return response()->json(['error' => 'Invalid secret'], 403);
        }

        DB::table('users')->where('id', $userId)->update([
            'gpmc_import_progress' => json_encode([
                'status' => $status,
                'processed' => (int)$processed,
                'message' => $message
            ])
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get file data for preview modal (AJAX)
     */
    public function preview($id)
    {
        $user = Auth::user();

        // Admin can preview any file, regular users only their own
        $query = DB::table('files')->where('id', $id);
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }
        $file = $query->first();

        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->json([
            'id' => $file->id,
            'filename' => $file->filename,
            'slug' => $file->slug,
            'file_size' => \App\Helpers\FormatHelper::formatBytes($file->file_size),
            'resolution' => $file->resolution,
            'media_url' => $file->media_url,
            'video_url' => route('player.watch', $file->slug),
            'download_url' => route('player.download', $file->slug),
        ]);
    }

    /**
     * Incremental sync - fetch only changes since last state_token
     * Uses database locking to prevent race conditions
     */
    public function syncGpmc()
    {
        $user = Auth::user();

        // Check if user has GPMC auth data
        if (empty($user->gpmc_auth_data)) {
            return response()->json([
                'success' => true,
                'inserted' => 0,
                'deleted' => 0,
                'message' => 'Google Photos not connected'
            ]);
        }

        // Use database lock to prevent race conditions
        $lockAcquired = DB::table('users')
            ->where('id', $user->id)
            ->where('sync_in_progress', false)
            ->update([
                'sync_in_progress' => true,
                'sync_queued' => false
            ]);

        if (!$lockAcquired) {
            // Another sync is running, queue this one
            DB::table('users')->where('id', $user->id)->update(['sync_queued' => true]);
            return response()->json([
                'success' => true,
                'inserted' => 0,
                'deleted' => 0,
                'status' => 'queued',
                'message' => 'Sync already in progress, queued for next run'
            ]);
        }

        try {
            $result = $this->runSyncLogic($user);

            // After sync completes, check if another sync was queued
            $userAfter = DB::table('users')->where('id', $user->id)->first();
            $runAgain = $userAfter->sync_queued ?? false;

            // Release lock and update last_sync_at
            DB::table('users')->where('id', $user->id)->update([
                'sync_in_progress' => false,
                'last_sync_at' => now(),
                'sync_queued' => false
            ]);

            // If queued, run again (recursive but limited by lock)
            if ($runAgain) {
                return $this->syncGpmc();
            }

            return response()->json($result);
        } catch (\Exception $e) {
            // Release lock on error
            DB::table('users')->where('id', $user->id)->update([
                'sync_in_progress' => false,
                'sync_queued' => false
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Core sync logic - separated for reuse
     */
    private function runSyncLogic($user): array
    {
        // If no state_token, get one first
        $stateToken = $user->gpmc_state_token;
        if (empty($stateToken)) {
            $tempFile = storage_path('app/temp_auth_token_' . $user->id . '_' . time() . '.txt');
            file_put_contents($tempFile, $user->gpmc_auth_data);

            $pythonPath = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'python' : 'python3.14';
            $scriptPath = base_path('api/gpmc_wrapper.py');

            $command = sprintf(
                '%s %s --get_token --auth_file=%s 2>&1',
                escapeshellarg($pythonPath),
                escapeshellarg($scriptPath),
                escapeshellarg($tempFile)
            );

            $output = shell_exec($command);
            if (file_exists($tempFile)) unlink($tempFile);

            $tokenResult = json_decode($output, true);

            if ($tokenResult && $tokenResult['success'] && !empty($tokenResult['state_token'])) {
                $stateToken = $tokenResult['state_token'];
                DB::table('users')->where('id', $user->id)->update([
                    'gpmc_state_token' => $stateToken
                ]);
            } else {
                throw new \Exception('Failed to get sync token');
            }
        }

        // Write auth data to temp file
        $tempFile = storage_path('app/temp_auth_' . $user->id . '_' . time() . '.txt');
        file_put_contents($tempFile, $user->gpmc_auth_data);

        $pythonPath = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'python' : 'python3.14';
        $scriptPath = base_path('api/gpmc_wrapper.py');

        $command = sprintf(
            '%s %s --sync --auth_file=%s --state_token=%s 2>&1',
            escapeshellarg($pythonPath),
            escapeshellarg($scriptPath),
            escapeshellarg($tempFile),
            escapeshellarg($stateToken)
        );

        $output = shell_exec($command);

        if (file_exists($tempFile)) {
            unlink($tempFile);
        }

        $result = json_decode($output, true);

        if (!$result || !isset($result['success'])) {
            throw new \Exception('Failed to parse sync response');
        }

        if (!$result['success']) {
            throw new \Exception($result['message'] ?? 'Sync failed');
        }

        // Insert new files and collect them for return
        $inserted = 0;
        $insertedFiles = [];
        $newFiles = $result['new_files'] ?? [];
        foreach ($newFiles as $file) {
            do {
                $slug = \Illuminate\Support\Str::random(12);
            } while (DB::table('files')->where('slug', $slug)->exists());

            $fileData = [
                'user_id' => $user->id,
                'filename' => $file['filename'],
                'slug' => $slug,
                'file_size' => $file['file_size'],
                'media_id' => $file['media_id'],
                'timestamp' => $file['timestamp'],
                'media_url' => $file['media_url'],
                'dedup_id' => $file['dedup_id'],
                'resolution' => $file['resolution'],
                'rip_type' => $file['rip_type'],
                'codec' => $file['codec'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $insertResult = DB::table('files')->insertOrIgnore($fileData);
            if ($insertResult) {
                $inserted++;
                $insertedFiles[] = [
                    'id' => DB::getPdo()->lastInsertId(),
                    'filename' => $file['filename'],
                    'slug' => $slug,
                    'file_size' => $file['file_size'],
                    'formatted_size' => \App\Helpers\FormatHelper::formatBytes($file['file_size']),
                    'resolution' => $file['resolution'],
                    'formatted_date' => 'just now',
                    'media_url' => $file['media_url'],
                ];
            }
        }

        // Delete removed files
        $deleted = 0;
        $deletedKeys = $result['deleted_keys'] ?? [];
        if (!empty($deletedKeys)) {
            $deleted = DB::table('files')
                ->where('user_id', $user->id)
                ->whereIn('media_id', $deletedKeys)
                ->delete();
        }

        // Update state_token
        DB::table('users')->where('id', $user->id)->update([
            'gpmc_state_token' => $result['state_token'] ?? $user->gpmc_state_token
        ]);

        return [
            'success' => true,
            'inserted' => $inserted,
            'deleted' => $deleted,
            'files' => $insertedFiles,
            'message' => ($inserted > 0 || $deleted > 0) ? "Synced: +{$inserted} -{$deleted}" : 'Already up to date'
        ];
    }

    /**
     * Get current sync status for polling
     */
    public function syncStatus()
    {
        $user = Auth::user();

        return response()->json([
            'syncing' => (bool) $user->sync_in_progress,
            'queued' => (bool) $user->sync_queued,
            'last_sync' => $user->last_sync_at ? \Carbon\Carbon::parse($user->last_sync_at)->diffForHumans() : null
        ]);
    }

    /**
     * Manual sync trigger (from UI button)
     */
    public function manualSync()
    {
        return $this->syncGpmc();
    }
}
