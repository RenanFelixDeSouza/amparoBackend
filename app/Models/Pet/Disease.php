<?php

namespace App\Models\Pet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disease extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'created_by',
    ];

    public function pets()
    {
        return $this->belongsToMany(Pet::class, 'pivot_pet_disease')
                    ->withTimestamps();
    }
}