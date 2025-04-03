<?php

namespace App\Repositories\Pet;

use App\Models\Pet\Pet;

class PetRepository
{
    public function create(array $data)
    {
        return Pet::create($data);
    }
}