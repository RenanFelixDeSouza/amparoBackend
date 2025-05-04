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
    public function createSpecie(array $data)
    {
        return $this->specieRepository->create($data);
    }

    public function getAllSpecies(array $filters, array $pagination)
    {
        return $this->specieRepository->getAll($filters, $pagination);
    }
}