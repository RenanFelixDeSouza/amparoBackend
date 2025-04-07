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

    public function getAllRaces(?string $search = '', int $limit = 5)
    {
        $search = $search ?? '';

        return $this->raceRepository->getAll($search, $limit);
    }
}