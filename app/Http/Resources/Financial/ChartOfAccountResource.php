<?php

namespace App\Http\Resources\Financial;

use Illuminate\Http\Resources\Json\JsonResource;

class ChartOfAccountResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'account_code' => $this->account_code,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'level' => $this->level,
            'path' => $this->path,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'full_path' => $this->when($this->path, function () {
                return array_filter(explode('/', $this->path));
            }, []),
            'parent' => new ChartOfAccountResource($this->whenLoaded('parent')),
            'children' => ChartOfAccountResource::collection($this->whenLoaded('children'))
        ];
    }
}