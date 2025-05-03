<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MonthlySubscriptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'subscription_plan' => $this->subscriptionPlan,
            'wallet_movement' => $this->walletMovement,
            'start_date' => $this->start_date,
            'next_due_date' => $this->next_due_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}