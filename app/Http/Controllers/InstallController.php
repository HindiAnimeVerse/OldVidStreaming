<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallController extends Controller
{
    /**
     * Path to the installation config file
     */
    private function getConfigPath()
    {
        return config_path('installed.php');
    }

    /**
     * Show the installation wizard
     */
    public function index()
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect('/');
        }

        return view('install.wizard');
    }

    /**
     * Check server requirements
     */
    public function checkRequirements()
    {
        // Check PHP requirements
        $phpOk = version_compare(phpversion(), '8.1.0', '>=');
        $mysqlOk = extension_loaded('pdo_mysql');
        $mbstringOk = extension_loaded('mbstring');
        $opensslOk = extension_loaded('openssl');
        $curlOk = extension_loaded('curl');
        $storageOk = is_writable(storage_path()) && is_writable(config_path());

        // Check if shell_exec is enabled
        $shellExecOk = function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(',', ini_get('disable_functions'))));

        // Check Python requirements
        $pythonCheck = $this->checkPythonEnvironment();

        return response()->json([
            'php_version' => phpversion(),
            'php_ok' => $phpOk,
            'mysql_ok' => $mysqlOk,
            'mbstring_ok' => $mbstringOk,
            'openssl_ok' => $opensslOk,
            'curl_ok' => $curlOk,
            'storage_ok' => $storageOk,
            'shell_exec_ok' => $shellExecOk,
            'python_version' => $pythonCheck['version'],
            'python_ok' => $pythonCheck['ok'],
            'rich_ok' => $pythonCheck['rich'],
            'requests_ok' => $pythonCheck['requests'],
            'bbpb_ok' => $pythonCheck['bbpb'],
        ]);
    }

    /**
     * Check Python environment and packages
     */
    private function checkPythonEnvironment()
    {
        $result = [
            'version' => null,
            'ok' => false,
            'rich' => false,
            'requests' => false,
            'bbpb' => false,
        ];

        // Determine Python command based on OS
        $pythonCmd = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'python' : 'python3.14';

        // Check Python version
        $versionOutput = @shell_exec("{$pythonCmd} --version 2>&1");
        if ($versionOutput && preg_match('/Python\s+(\d+\.\d+\.\d+)/', $versionOutput, $matches)) {
            $result['version'] = $matches[1];
            $result['ok'] = version_compare($matches[1], '3.11.0', '>=');
        }

        // Check if packages are installed (only if Python is available)
        if ($result['ok']) {
            // Check rich
            $richCheck = @shell_exec("{$pythonCmd} -c \"import rich; print('ok')\" 2>&1");
            $result['rich'] = trim($richCheck) === 'ok';

            // Check requests
            $requestsCheck = @shell_exec("{$pythonCmd} -c \"import requests; print('ok')\" 2>&1");
            $result['requests'] = trim($requestsCheck) === 'ok';

            // Check blackboxprotobuf (bbpb)
            $bbpbCheck = @shell_exec("{$pythonCmd} -c \"import blackboxprotobuf; print('ok')\" 2>&1");
            $result['bbpb'] = trim($bbpbCheck) === 'ok';
        }

        return $result;
    }

    /**
     * Test database connection
     */
    public function testDatabase(Request $request)
    {
        $request->validate([
            'host' => 'required',
            'port' => 'required',
            'database' => 'required',
            'username' => 'required',
        ]);

        try {
            $connection = @mysqli_connect(
                $request->host,
                $request->username,
                $request->password ?? '',
                $request->database,
                $request->port
            );

            if (!$connection) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not connect to database: ' . mysqli_connect_error()
                ]);
            }

            mysqli_close($connection);

            return response()->json([
                'success' => true,
                'message' => 'Connection successful'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Save database configuration to PHP config file
     */
    public function saveDatabase(Request $request)
    {
        $request->validate([
            'host' => 'required',
            'port' => 'required',
            'database' => 'required',
            'username' => 'required',
        ]);

        try {
            // Write config to installed.php
            $configContent = "<?php\n\n";
            $configContent .= "/**\n";
            $configContent .= " * VidStreaming Installation Configuration\n";
            $configContent .= " * \n";
            $configContent .= " * Generated by installation wizard on " . date('Y-m-d H:i:s') . "\n";
            $configContent .= " * DO NOT EDIT MANUALLY unless you know what you're doing.\n";
            $configContent .= " */\n\n";
            $configContent .= "return [\n";
            $configContent .= "    'installed' => false,\n\n";
            $configContent .= "    'database' => [\n";
            $configContent .= "        'host' => '" . addslashes($request->host) . "',\n";
            $configContent .= "        'port' => '" . addslashes($request->port) . "',\n";
            $configContent .= "        'database' => '" . addslashes($request->database) . "',\n";
            $configContent .= "        'username' => '" . addslashes($request->username) . "',\n";
            $configContent .= "        'password' => '" . addslashes($request->password ?? '') . "',\n";
            $configContent .= "    ],\n";
            $configContent .= "];\n";

            File::put($this->getConfigPath(), $configContent);

            // Clear Laravel config cache
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }

            Artisan::call('config:clear');

            // Set runtime database config
            config([
                'database.connections.mysql.host' => $request->host,
                'database.connections.mysql.port' => $request->port,
                'database.connections.mysql.database' => $request->database,
                'database.connections.mysql.username' => $request->username,
                'database.connections.mysql.password' => $request->password ?? '',
            ]);

            DB::purge('mysql');
            DB::reconnect('mysql');

            // Run migrations (fresh - drops all tables first)
            Artisan::call('migrate:fresh', ['--force' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Database configured successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to configure database: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Create admin account
     */
    public function createAdmin(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
        ]);

        try {
            // Create admin user
            DB::table('users')->insert([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => true,
                'can_use_gpmc' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Mark as installed in the config file
            $this->markInstalled();

            return response()->json([
                'success' => true,
                'message' => 'Admin account created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create admin: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check if application is installed
     */
    private function isInstalled()
    {
        $configPath = $this->getConfigPath();

        if (!file_exists($configPath)) {
            return false;
        }

        $config = include $configPath;
        return $config['installed'] ?? false;
    }

    /**
     * Mark application as installed
     */
    private function markInstalled()
    {
        $config = include $this->getConfigPath();

        $configContent = "<?php\n\n";
        $configContent .= "/**\n";
        $configContent .= " * VidStreaming Installation Configuration\n";
        $configContent .= " * \n";
        $configContent .= " * Generated by installation wizard on " . date('Y-m-d H:i:s') . "\n";
        $configContent .= " * DO NOT EDIT MANUALLY unless you know what you're doing.\n";
        $configContent .= " */\n\n";
        $configContent .= "return [\n";
        $configContent .= "    'installed' => true,\n\n";
        $configContent .= "    'database' => [\n";
        $configContent .= "        'host' => '" . addslashes($config['database']['host']) . "',\n";
        $configContent .= "        'port' => '" . addslashes($config['database']['port']) . "',\n";
        $configContent .= "        'database' => '" . addslashes($config['database']['database']) . "',\n";
        $configContent .= "        'username' => '" . addslashes($config['database']['username']) . "',\n";
        $configContent .= "        'password' => '" . addslashes($config['database']['password']) . "',\n";
        $configContent .= "    ],\n";
        $configContent .= "];\n";

        File::put($this->getConfigPath(), $configContent);

        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        Artisan::call('config:clear');
    }
}
