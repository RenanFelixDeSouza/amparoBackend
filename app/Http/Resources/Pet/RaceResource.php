<?php

namespace App\Http\Resources\Pet;

use Illuminate\Http\Resources\Json\JsonResource;

class RaceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}