<?php
namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Services\Financial\CashFlowService;
use App\Http\Resources\Financial\CashFlowResource;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    protected $cashFlowService;

    public function __construct(CashFlowService $cashFlowService)
    {
        $this->cashFlowService = $cashFlowService;
    }

    public function index(Request $request)
    {
        $cashFlows = $this->cashFlowService->getAll($request->all());
        return CashFlowResource::collection($cashFlows);
    }

    public function show($id)
    {
        $cashFlow = $this->cashFlowService->getById($id);
        return new CashFlowResource($cashFlow);
    }

    public function store(Request $request)
    {
        $cashFlow = $this->cashFlowService->create($request->all());
        return new CashFlowResource($cashFlow);
    }

    public function update(Request $request, $id)
    {
        $cashFlow = $this->cashFlowService->update($id, $request->all());
        return new CashFlowResource($cashFlow);
    }

    public function destroy($id)
    {
        $this->cashFlowService->delete($id);
        return response()->noContent();
    }
}