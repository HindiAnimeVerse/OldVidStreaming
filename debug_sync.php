<?php

/**
 * Test sync endpoint directly
 */
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Get user with auth data
$user = DB::table('users')
    ->whereNotNull('gpmc_auth_data')
    ->where('gpmc_auth_data', '!=', '')
    ->first();

if (!$user) {
    echo "ERROR: No user with gpmc_auth_data found\n";
    exit(1);
}

echo "=== User Before ===\n";
echo "ID: {$user->id}\n";
echo "State Token: " . (empty($user->gpmc_state_token) ? 'EMPTY' : substr($user->gpmc_state_token, 0, 50) . '...') . "\n\n";

// Get state_token from API
$tempFile = storage_path('app/test_auth_' . time() . '.txt');
file_put_contents($tempFile, $user->gpmc_auth_data);

echo "Auth file created: $tempFile\n";
echo "Auth file exists: " . (file_exists($tempFile) ? 'YES' : 'NO') . "\n";
echo "Auth file size: " . filesize($tempFile) . " bytes\n\n";

$pythonPath = 'python';
$scriptPath = base_path('api/gpmc_wrapper.py');

$command = sprintf(
    '%s %s --get_token --auth_file=%s 2>&1',
    escapeshellarg($pythonPath),
    escapeshellarg($scriptPath),
    escapeshellarg($tempFile)
);

echo "Command: $command\n\n";

$output = shell_exec($command);

// Note: auth_file script should have deleted the file
echo "Output raw:\n---\n$output\n---\n\n";

$tokenResult = json_decode($output, true);

echo "Parsed JSON:\n";
if ($tokenResult) {
    print_r($tokenResult);

    if ($tokenResult['success'] && !empty($tokenResult['state_token'])) {
        echo "\n=== Saving State Token ===\n";

        $affected = DB::table('users')->where('id', $user->id)->update([
            'gpmc_state_token' => $tokenResult['state_token']
        ]);

        echo "Rows affected: $affected\n";

        // Verify
        $userAfter = DB::table('users')->where('id', $user->id)->first();
        echo "\n=== User After ===\n";
        echo "State Token: " . (empty($userAfter->gpmc_state_token) ? 'EMPTY' : substr($userAfter->gpmc_state_token, 0, 50) . '...') . "\n";
    } else {
        echo "Token result was not successful or state_token is empty\n";
    }
} else {
    echo "Failed to parse JSON\n";
    echo "JSON error: " . json_last_error_msg() . "\n";
}

// Cleanup
if (file_exists($tempFile)) {
    unlink($tempFile);
}
