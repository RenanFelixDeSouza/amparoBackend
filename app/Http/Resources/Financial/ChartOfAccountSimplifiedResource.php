<?php

namespace App\Http\Resources\Financial;

use Illuminate\Http\Resources\Json\JsonResource;

class ChartOfAccountSimplifiedResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'account_code' => $this->account_code,
            'name' => $this->name,
            'type' => $this->type,
            'parent_id' => $this->parent_id,
        ];
    }
}