<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        // Convert boolean-like values
        if ($setting->value === '1' || $setting->value === 'true') {
            return true;
        }
        if ($setting->value === '0' || $setting->value === 'false') {
            return false;
        }

        return $setting->value;
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value): void
    {
        // Convert booleans to string
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get all settings as array
     */
    public static function getAll(): array
    {
        return self::pluck('value', 'key')->toArray();
    }

    /**
     * Set multiple settings at once
     */
    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            self::set($key, $value);
        }
    }
}
