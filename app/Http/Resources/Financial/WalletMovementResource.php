<?php

namespace App\Http\Resources\Financial;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletMovementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'value' => $this->value,
            'comments' => $this->comments,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name
            ],
            'wallet' => new WalletResource($this->wallet),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}