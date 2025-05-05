<?php

namespace App\Http\Resources\Financial;

use Illuminate\Http\Resources\Json\JsonResource;

class MonthlySubscriptionInstallmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'subscriber_name' => $this->monthlySubscription->user->name,
            'total_installments' => $this->monthlySubscription->installments()->count(),
            'current_installment' => $this->installment_number,
            'installment_value' => $this->value,
            'plan_name' => $this->monthlySubscription->subscriptionPlan->name,
            'due_date' => $this->due_date,
            'payment_date' => $this->payment_date,
            'status' => $this->status
        ];
    }
}