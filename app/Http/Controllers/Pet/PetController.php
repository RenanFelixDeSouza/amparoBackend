<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\StorePetRequest;
use App\Http\Requests\Pet\UpdatePetRequest;
use App\Http\Resources\Pet\PetResource;
use App\Services\Pet\PetService;
use DB;
use Illuminate\Http\Request;

class PetController extends Controller
{
    protected $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    public function index(Request $request)
    {
        $filters = [
            'name' => $request->input('name', ''),
            'specie' => $request->input('specie', ''),
            'race' => $request->input('race', ''),
        ];

        $pagination = [
            'page' => $request->input('page', 1),
            'limit' => $request->input('limit', 10),
            'sort_column' => $request->input('sort_column', 'id'),
            'sort_order' => $request->input('sort_order', 'asc'),
        ];

        $pets = $this->petService->getAllPets($filters, $pagination);

        return PetResource::collection($pets);
    }

    public function store(StorePetRequest $request)
    {

        $pet = $this->petService->createPet($request->all());

        return response()->json([
            'message' => 'Pet created successfully',
            'pet' => $pet,
        ], 201);
    }

    public function update(UpdatePetRequest $request, $id)
    {
        $pet = $this->petService->updatePet($id, $request->all());

        return response()->json([
            'message' => 'Pet updated successfully',
            'pet' => $pet
        ]);
    }

    public function uploadPhoto(Request $request, $petId)
    {
        $result = $this->petService->uploadPhoto($request, $petId);

        return response()->json([
            'message' => $result['message'],
            'photo_url' => $result['photo_url'],
        ]);
    }

    public function deletePhoto(Request $request, $petId)
    {
        $result = $this->petService->deletePhoto($request, $petId);

        return response()->json([
            'message' => $result['message'],
        ]);
        
    }

}
