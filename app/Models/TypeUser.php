<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'description', 
    ];

    public $timestamps = false;

    // Relacionamento com User
    public function users()
    {
        return $this->hasMany(User::class, 'type_user_id');
    }
}
