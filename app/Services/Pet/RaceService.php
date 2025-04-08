<?php

namespace App\Services\Pet;

use App\Repositories\Pet\RaceRepository;

class RaceService
{
    protected $raceRepository;

    public function __construct(RaceRepository $raceRepository)
    {
        $this->raceRepository = $raceRepository;
    }

    public function createRace(array $data)
    {
        return $this->raceRepository->create($data);
    }

    public function getAllRaces(array $filters, array $pagination)
    {
        return $this->raceRepository->getAll($filters, $pagination);
    }
}