<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingRT extends Model
{
    protected $table = 'setting_rt';
    protected $fillable = ['key', 'value', 'deskripsi'];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $deskripsi = null)
    {
        return static::updateOrCreate(['key' => $key], [
            'value' => $value,
            'deskripsi' => $deskripsi,
        ]);
    }
}
