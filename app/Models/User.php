<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'user_name',
        'email',
        'password',
        'type_user_id',
        'address_id',
        'phone',
    ];

    protected $hidden = [
        'password',
    ];

    public static function find($id)
    {
        return static::query()->find($id);
    }

    public function typeUser()
    {
        return $this->belongsTo(TypeUser::class, 'type_user_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
}