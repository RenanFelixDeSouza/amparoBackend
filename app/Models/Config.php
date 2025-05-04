<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = [
        'module',
        'key_name',
        'value',
        'description'
    ];

    public static function getConfigValue($keyName)
    {
        $config = self::where('key_name', $keyName)->first();
        return $config ? $config->value : null;
    }

    public static function setConfigValue($keyName, $value, $module = 'financial', $description = null)
    {
        return self::updateOrCreate(
            ['key_name' => $keyName],
            [
                'value' => (string) $value,
                'module' => $module,
                'description' => $description
            ]
        );
    }

    public static function getFinancialConfigs()
    {
        $configs = self::where('module', 'financial')->get();
        $result = [];
        
        foreach ($configs as $config) {
            $result[$config->key_name] = $config->value;
        }
        
        return $result;
    }
}