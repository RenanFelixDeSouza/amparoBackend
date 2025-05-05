<?php

namespace App\Models\User;

use App\Models\Pet\Pet;
use App\Models\Address\Address;
use App\Models\Financial\MonthlySubscription;
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
        'photo',
        'is_active',
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

    public function pets()
    {
        return $this->belongsToMany(Pet::class, 'pivot_pet_users')
                    ->withPivot('action')
                    ->withTimestamps();
    }

    public function monthlySubscriptions()
    {
        return $this->hasMany(MonthlySubscription::class);
    }
}