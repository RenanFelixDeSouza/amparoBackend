<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\StorePetRequest;
use App\Services\Pet\PetService;
use Illuminate\Http\Request;

class PetController extends Controller
{
    protected $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    public function store(StorePetRequest $request)
{

    $pet = $this->petService->createPet($request->all());

    return response()->json([
        'message' => 'Pet created successfully',
        'pet' => $pet,
    ], 201);
}

    public function uploadPhoto(Request $request, $petId)
    {
        $result = $this->petService->uploadPhoto($request, $petId);

        return response()->json([
            'message' => $result['message'],
            'photo_url' => $result['photo_url'],
        ]);
    }   
}
