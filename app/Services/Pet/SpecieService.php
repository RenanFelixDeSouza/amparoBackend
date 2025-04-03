<?php

namespace App\Services\Pet;

use App\Repositories\Pet\SpecieRepository;

class SpecieService
{
    protected $specieRepository;

    public function __construct(SpecieRepository $specieRepository)
    {
        $this->specieRepository = $specieRepository;
    }

    public function getAllSpecies(?string $search = '', int $limit = 5)
    {
        $search = $search ?? '';

        return $this->specieRepository->getAll($search, $limit);
    }
}