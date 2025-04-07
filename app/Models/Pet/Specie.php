<?php

namespace App\Models\Pet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specie extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'created_by',
    ];

    public function pets()
    {
        return $this->hasMany(Pet::class, 'specie_id');
    }
}