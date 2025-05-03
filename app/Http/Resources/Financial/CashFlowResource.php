<?php

namespace App\Http\Resources\Financial;

use Illuminate\Http\Resources\Json\JsonResource;

class CashFlowResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'flow_type' => $this->flow_type,
            'description' => $this->description,
            'value' => $this->value,
            'date' => $this->date->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'comments' => $this->comments,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'wallet_movement' => $this->whenLoaded('walletMovement', function() {
                return [
                    'id' => $this->walletMovement->id,
                    'type' => $this->walletMovement->type,
                    'value' => $this->walletMovement->value,
                    'wallet_id' => $this->walletMovement->wallet_id,
                ];
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}