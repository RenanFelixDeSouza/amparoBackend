<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletMovementResource;
use App\Services\Wallet\WalletMovementService;
use App\Http\Requests\Wallet\StoreWalletMovementRequest;
use App\Http\Requests\Wallet\UpdateWalletMovementRequest;
use Illuminate\Http\Request;

class WalletMovementController extends Controller
{
    protected $movementService;

    public function __construct(WalletMovementService $movementService)
    {
        $this->movementService = $movementService;
    }

    public function create(StoreWalletMovementRequest $request)
    {
        try {
            $result = $this->movementService->createMovement($request->validated());
            return response()->json($result, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar movimento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getMovements($walletId)
    {
        try {
            $result = $this->movementService->getMovements($walletId);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar movimentos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listAll(Request $request)
    {
        try {
            $filters = [
                'wallet_id' => $request->input('wallet_id'),
                'type' => $request->input('type'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'sort_column' => $request->input('sort_column', 'created_at'),
                'sort_order' => $request->input('sort_order', 'desc'),
                'page' => $request->input('page', 1)
            ];

            $result = $this->movementService->listAllMovements($filters);
            
            return WalletMovementResource::collection($result);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar movimentações',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}