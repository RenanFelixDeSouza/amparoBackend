<?php

namespace App\Services\Company;

use App\Repositories\Company\CompanyRepository;

class CompanyService
{
    protected $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * Criar uma nova empresa.
     *
     * @param array $data
     * @return mixed
     */
    public function createCompany(array $data)
    {

        return $this->companyRepository->createWithAddress($data);
    }

    public function getAllCompanies(array $filters, array $pagination)
    {
        $query = $this->companyRepository->query();

        if (!empty($filters['company_name'])) {
            $query->where('company_name', 'like', '%' . $filters['company_name'] . '%');
        }

        if (!empty($filters['fantasy_name'])) {
            $query->where('fantasy_name', 'like', '%' . $filters['fantasy_name'] . '%');
        }

        if (!empty($filters['cnpj'])) {
            $query->where('cnpj', 'like', '%' . $filters['cnpj'] . '%');
        }

        return $query->orderBy($pagination['sort_column'], $pagination['sort_order'])
                     ->paginate($pagination['limit'], ['*'], 'page', $pagination['page']);
    }
}