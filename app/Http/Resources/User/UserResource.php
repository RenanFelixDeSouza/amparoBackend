<?php
namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_name' => $this->user_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'photo_url' => $this->photo ? asset('storage/' . $this->photo) : "",
            'address' => $this->address ? [
                'zip_code' => $this->address->zip_code,
                'street' => $this->address->street,
                'number' => $this->address->number,
                'complement' => $this->address->complement,
                'district_name' => $this->address->district_name,
                'city_id' => $this->address->city_id,
                'city_name' => $this->address->city ? $this->address->city->name : null,
                'city_federative_unit' => $this->address->city ? $this->address->city->federative_unit : null,
            ] : null,
        ];
    }
}
