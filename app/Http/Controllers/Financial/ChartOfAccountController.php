<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Http\Requests\Financial\ChartOfAccount\StoreChartOfAccountRequest;
use App\Http\Requests\Financial\ChartOfAccount\UpdateChartOfAccountRequest;
use App\Services\Financial\ChartOfAccountService;
use Illuminate\Http\Request;
use App\Http\Resources\Financial\ChartOfAccountResource;

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
                'name' => $request->input('name'),
                'parent_id' => $request->input('parent_id'),
                'level' => $request->input('level')
            ];

            $pagination = [
                'page' => $request->input('page', 1),
                'limit' => $request->input('limit', 5),
                'sort_by' => $request->input('sort_by', 'created_at'),
                'sort_order' => $request->input('sort_order', 'desc')
            ];

            $result = $this->chartOfAccountService->getAllAccounts($filters, $pagination);

            return response()->json([
                'data' => ChartOfAccountResource::collection($result['accounts']),
                'meta' => $result['summary']
            ]);
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

    public function show($id)
    {
        try {
            $account = $this->chartOfAccountService->getAccountById($id);
            return new ChartOfAccountResource($account);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar plano de contas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getChildren($id)
    {
        try {
            $children = $this->chartOfAccountService->getDirectChildren($id);
            return ChartOfAccountResource::collection($children);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar contas filhas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getParents($id)
    {
        try {
            $parents = $this->chartOfAccountService->getParentHierarchy($id);
            return ChartOfAccountResource::collection($parents);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar hierarquia de pais',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}