<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
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

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $limit = $request->input('limit', 5);

        $species = $this->specieService->getAllSpecies($search, $limit);

        return SpecieResource::collection($species);
    }
}