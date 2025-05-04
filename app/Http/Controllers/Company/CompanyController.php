<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Services\Company\CompanyService;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Requests\Company\StoreCompanyRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * Fetch company details by CNPJ using an external API.
     */
    public function getCompanyByCnpj($cnpj)
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return response()->json(['error' => 'CNPJ inválido.'], 400);
        }

        $response = Http::get("https://receitaws.com.br/v1/cnpj/{$cnpj}");

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Erro ao buscar CNPJ.'], 500);
    }

    /**
     * Store a new company in the database.
     */
    public function store(StoreCompanyRequest $request)
    {
        $validatedData = $request->validated();

        $company = $this->companyService->createCompany($validatedData);

        return new CompanyResource($company);
    }

    /**
     * Get a list of companies with filters and pagination.
     */
    public function index(Request $request)
    {
        $filters = [
            'company_name' => $request->input('company_name', ''),
            'fantasy_name' => $request->input('fantasy_name', ''),
            'cnpj' => $request->input('cnpj', ''),
        ];

        $pagination = [
            'page' => $request->input('page', 1),
            'limit' => $request->input('limit', 10),
            'sort_column' => $request->input('sort_column', 'id'),
            'sort_order' => $request->input('sort_order', 'asc'),
        ];

        $companies = $this->companyService->getAllCompanies($filters, $pagination);

        return CompanyResource::collection($companies);
    }
}