<?php

namespace App\Http\Controllers\ChartOfAccount;

use App\Http\Controllers\Controller;
use App\Services\ChartOfAccount\ChartOfAccountService;
use App\Http\Requests\ChartOfAccount\StoreChartOfAccountRequest;
use App\Http\Requests\ChartOfAccount\UpdateChartOfAccountRequest;
use Illuminate\Http\Request;
use App\Http\Resources\ChartOfAccountResource;

class ChartOfAccountController extends Controller
{
    protected $chartOfAccountService;

    public function __construct(ChartOfAccountService $chartOfAccountService)
    {
        $this->chartOfAccountService = $chartOfAccountService;
    }

    public function index(Request $request)
    {
        try {
            $filters = [
                'type' => $request->input('type'),
                'parent_id' => $request->input('parent_id'),
                'level' => $request->input('level')
            ];

            $pagination = [
                'page' => $request->input('page', 1),
                'limit' => $request->input('limit', 10)
            ];

            $result = $this->chartOfAccountService->getAllAccounts($filters, $pagination);

            return ChartOfAccountResource::collection($result['accounts'])
                ->additional(['summary' => $result['summary']]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar plano de contas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function hierarchy()
    {
        try {
            $accounts = $this->chartOfAccountService->getHierarchy();
            return ChartOfAccountResource::collection($accounts);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar hierarquia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create(StoreChartOfAccountRequest $request)
    {
        try {
            $result = $this->chartOfAccountService->createAccount($request->validated());
            
            return response()->json([
                'message' => 'Plano de contas criado com sucesso',
                'data' => new ChartOfAccountResource($result['account'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar plano de contas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateChartOfAccountRequest $request, $id)
    {
        try {
            $result = $this->chartOfAccountService->updateAccount($id, $request->validated());
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar conta',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}