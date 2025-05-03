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
}