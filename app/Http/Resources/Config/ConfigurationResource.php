<?php

namespace App\Http\Resources\Config;

use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'module' => $this->module,
            'key_name' => $this->key_name,
            'value' => $this->value,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}