<?php

namespace App\Models\Pet;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'photo',
        'is_castrated',
        'birth_date',
        'race_id',
        'specie_id',
        'adoption_user_id',
        'temporary_home_user_id'
    ];

    public function race()
    {
        return $this->belongsTo(Race::class, 'race_id');
    }

    public function specie()
    {
        return $this->belongsTo(Specie::class, 'specie_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'pivot_pet_users')
            ->withPivot('action')
            ->withTimestamps();
    }

    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'pivot_pet_disease')
            ->withTimestamps();
    }

    // Relação com o usuário que adotou o pet
    public function adoptionUser()
    {
        return $this->belongsTo(User::class, 'adoption_user_id');
    }

    // Relação com o usuário que é lar temporário do pet
    public function temporaryHomeUser()
    {
        return $this->belongsTo(User::class, 'temporary_home_user_id');
    }
}