<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\StoreRaceRequest;
use App\Http\Resources\Pet\RaceResource;
use App\Services\Pet\RaceService;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    protected $raceService;

    public function __construct(RaceService $raceService)
    {
        $this->raceService = $raceService;
    }

    public function store(StoreRaceRequest $request)
    {
        $validatedData = $request->validated();

        $race = $this->raceService->createRace($validatedData);

        return new RaceResource($race);
    }

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $limit = $request->input('limit', 5);

        $races = $this->raceService->getAllRaces($search, $limit);

        return RaceResource::collection($races);
    }
}