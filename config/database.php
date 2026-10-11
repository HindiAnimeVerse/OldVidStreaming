<?php

use Illuminate\Support\Str;

// Load custom installation config
$installed = include __DIR__ . '/installed.php';

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    */

    'default' => 'mysql',

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    */

    'connections' => [

        'mysql' => [
            'driver' => 'mysql',
            'host' => $installed['database']['host'] ?? '127.0.0.1',
            'port' => $installed['database']['port'] ?? '3306',
            'database' => $installed['database']['database'] ?? '',
            'username' => $installed['database']['username'] ?? '',
            'password' => $installed['database']['password'] ?? '',
            'unix_socket' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => null,
            ]) : [],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    */

    'redis' => [

        'client' => 'phpredis',

        'options' => [
            'cluster' => 'redis',
            'prefix' => 'vidstreaming-database-',
            'persistent' => false,
        ],

        'default' => [
            'host' => '127.0.0.1',
            'password' => null,
            'port' => '6379',
            'database' => '0',
        ],

        'cache' => [
            'host' => '127.0.0.1',
            'password' => null,
            'port' => '6379',
            'database' => '1',
        ],

    ],

];
