<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Format bytes into human-readable size
     * Automatically scales from Bytes to PB
     * 
     * @param int $bytes Size in bytes
     * @param int $precision Decimal precision (default: 2)
     * @return string Formatted size with unit
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB'];
        $base = 1024;

        $power = floor(log($bytes, $base));
        $power = min($power, count($units) - 1);

        $size = $bytes / pow($base, $power);

        // Remove trailing zeros for cleaner display
        $formatted = number_format($size, $precision);
        $formatted = rtrim(rtrim($formatted, '0'), '.');

        return $formatted . ' ' . $units[$power];
    }

    /**
     * Parse human-readable size to bytes
     * 
     * @param string $size Size string like "1.5 GB"
     * @return int Size in bytes
     */
    public static function parseBytes(string $size): int
    {
        $size = trim($size);
        $units = ['B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6];

        preg_match('/^([\d.]+)\s*([A-Z]+)?$/i', $size, $matches);

        if (!isset($matches[1])) {
            return 0;
        }

        $value = (float) $matches[1];
        $unit = strtoupper($matches[2] ?? 'B');

        if (!isset($units[$unit])) {
            return (int) $value;
        }

        return (int) ($value * pow(1024, $units[$unit]));
    }
}
