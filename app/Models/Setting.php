<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = Cache::remember("setting_{$key}", 3600, function () use ($key) {
            return self::where('key', $key)->first();
        });

        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general', ?string $label = null, ?string $description = null): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
                'group' => $group,
                'label' => $label,
                'description' => $description,
            ]
        );

        Cache::forget("setting_{$key}");

        return $setting;
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup(string $group)
    {
        return self::where('group', $group)->get();
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'json', 'array' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Get value attribute with proper casting
     */
    public function getTypedValueAttribute()
    {
        return self::castValue($this->value, $this->type);
    }
}
