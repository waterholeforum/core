<?php

namespace Waterhole\Models;

class Config extends Model
{
    protected $table = 'config';
    protected $primaryKey = 'key';

    protected $casts = [
        'value' => 'json',
    ];

    public static function get(string $key, $default = null)
    {
        return static::find($key)?->value ?: $default;
    }
}
