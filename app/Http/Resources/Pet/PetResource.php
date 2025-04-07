<?php

namespace App\Http\Resources\Pet;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Pet\PetRepository;

class PetResource extends JsonResource
{
    protected $petRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->petRepository = app(PetRepository::class);
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'specie' => $this->specie->description ?? null,
            'photo_url' => $this->photo ? asset('storage/' . $this->photo) : null,
            'race' => $this->race->description ?? null,
            'birth_date' => $this->birth_date,
            'is_castrated' => $this->is_castrated,
            'adoptions' => $this->petRepository->hasAdoption($this->id),
        ];
    }
}