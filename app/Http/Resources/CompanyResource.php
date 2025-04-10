<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transformar o recurso em um array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_name' => $this->company_name,
            'fantasy_name' => $this->fantasy_name,
            'cnpj' => $this->cnpj,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address ? [
                'zip_code' => $this->address->zip_code,
                'street' => $this->address->street,
                'number' => $this->address->number,
                'complement' => $this->address->complement,
                'district_name' => $this->address->district_name,
                'city_id' => $this->address->city_id,
                'city_name' => $this->address->city->name,
                'city_federative_unit' => $this->address->city->federative_unit,
            ] : null,
        ];
    }
}