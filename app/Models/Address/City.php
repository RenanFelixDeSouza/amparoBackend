<?php

namespace App\Models\Address;

use App\Models\Address\Address;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'federative_unit',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class, 'city_id');
    }
}
