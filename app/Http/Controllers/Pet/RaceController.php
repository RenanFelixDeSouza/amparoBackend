<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\Race\StoreRaceRequest;
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
        $filters = [
            'description' => $request->input('description', ''),
            'search' => $request->input('search', ''),
        ];

        $pagination = [
            'page' => $request->input('page', 1),
            'limit' => $request->input('limit', 10),
            'sort_column' => $request->input('sort_column', 'id'),
            'sort_order' => $request->input('sort_order', 'asc'),
        ];


        $races = $this->raceService->getAllRaces($filters, $pagination);

        return RaceResource::collection($races);
    }
}