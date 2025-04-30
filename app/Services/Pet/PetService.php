<?php

namespace App\Services\Pet;

use App\Models\Pet\Pet;
use App\Repositories\Pet\PetRepository;
use DB;
use Illuminate\Support\Facades\Storage;

class PetService
{
    protected $petRepository;

    public function __construct(PetRepository $petRepository)
    {
        $this->petRepository = $petRepository;
    }

    public function getAllPets(array $filters, array $pagination)
    {
        return $this->petRepository->getAll($filters, $pagination);
    }

    public function createPet($request)
    {
        $data = is_array($request) ? $request : $request->all();
        $data['user_id'] = auth()->user()->id;

        $pet = $this->petRepository->create($data);

        return $pet;
    }

    public function uploadPhoto($request, $petId)
    {
        $pet = Pet::findOrFail($petId);

        if ($pet->photo) {
            Storage::disk('public')->delete($pet->photo);
        }

        $timestamp = now()->format('YmdHis');
        $fileName = str_replace(' ', '_', strtolower($pet->name)) . "_{$timestamp}.png";

        $path = $request->file('photo')->storeAs('uploads/pets', $fileName, 'public');
        $pet->photo = $path;
        $pet->save();

        return [
            'message' => 'Foto do pet enviada com sucesso!',
            'photo_url' => asset('storage/' . $path),
        ];
    }

    public function deletePhoto($request, $petId)
    {
        $pet = Pet::findOrFail($petId);

        DB::beginTransaction();

        try {
            if ($pet->photo) {
                Storage::disk('public')->delete($pet->photo);
            }

            $pet->photo = null;
            $pet->save();

            DB::commit();

            return ['message' => 'foto do usuario deletado com sucesso!'];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatePet($id, $data)
    {
        return $this->petRepository->update($id, $data);
    }
}