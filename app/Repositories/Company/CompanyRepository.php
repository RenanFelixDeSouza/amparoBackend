<?php

namespace App\Repositories\Company;

use App\Models\Address\Address;
use App\Models\Company\Company;

class CompanyRepository
{
    protected $model;

    public function __construct(Company $model)
    {
        $this->model = $model;
    }

    /**
     * Criar uma empresa com endereço associado.
     *
     * @param array $companyData
     * @return Company
     */
    public function createWithAddress(array $companyData)
    {
        // Extrair os dados do endereço
        $addressData = [
            'zip_code' => $companyData['zip_code'],
            'street' => $companyData['street'],
            'number' => $companyData['number'],
            'complement' => $companyData['complement'],
            'district_name' => $companyData['district_name'],
            'city_id' => $companyData['city_id'],
        ];
    
        // Remover os dados do endereço do array principal
        unset(
            $companyData['zip_code'],
            $companyData['street'],
            $companyData['number'],
            $companyData['complement'],
            $companyData['district_name'],
            $companyData['city_id']
        );
    
        $companyData['ie_status'] = $companyData['ie_status'] ?? 'Não Contribuinte';
        $companyData['im_status'] = isset($companyData['im_status']) && $companyData['im_status'] === true ? 'insento' : "";
    
        $address = Address::create($addressData); 

        $companyData['address_id'] = $address->id;
        $company = $this->model->create($companyData);
    
    
    
        // Atualizar o address_id da empresa
        $company->address_id = $address->id;
        $company->save();
    
        // Retornar a empresa com o endereço carregado
        return $company->load('address');
    }

    public function query()
    {
        return $this->model->newQuery();
    }
}