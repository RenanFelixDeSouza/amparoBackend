<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_name' => $this->name,
            'photo_url' => $this->photo ? asset('storage/' . $this->photo) : "",
            'is_active' => $this->is_active,
            'type' => $this->typeUser,
        ];
    }
}