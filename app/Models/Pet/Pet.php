<?php

namespace App\Models\Pet;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'is_castrated',
        'age',
        'race_id',
        'specie_id',
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
}