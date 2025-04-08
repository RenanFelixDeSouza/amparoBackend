<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\StoreSpecieRequest;
use App\Http\Resources\Pet\SpecieResource;
use App\Services\Pet\SpecieService;
use Illuminate\Http\Request;

class SpecieController extends Controller
{
    protected $specieService;

    public function __construct(SpecieService $specieService)
    {
        $this->specieService = $specieService;
    }

    public function store(StoreSpecieRequest $request)
    {
        $validatedData = $request->validated();

        $specie = $this->specieService->createSpecie($validatedData);

        return new SpecieResource($specie);
    }
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search', ''),
            'description' => $request->input('description', ''),
        ];

        $pagination = [
            'page' => $request->input('page', 1),
            'limit' => $request->input('limit', 10),
            'sort_column' => $request->input('sort_column', 'species.description'),
            'sort_order' => $request->input('sort_order', 'asc'),
        ];

        $species = $this->specieService->getAllSpecies($filters, $pagination);

        return SpecieResource::collection($species);
    }
}