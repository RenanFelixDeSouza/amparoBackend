<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Services\Wallet\WalletService;
use App\Http\Requests\Wallet\StoreWalletRequest;
use App\Http\Requests\Wallet\UpdateWalletRequest;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function create(StoreWalletRequest $request)
    {
        try {
            $result = $this->walletService->createWallet($request->validated());
            return response()->json($result, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar carteira',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getBalance($walletId)
    {
        try {
            $result = $this->walletService->getBalance($walletId);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Carteira não encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function listAll(Request $request)
    {
        try {
            $filters = [
                'bank_name' => $request->input('bank_name', ''),
                'status' => $request->input('status', ''),
                'type' => $request->input('account_type', ''),
            ];

            $pagination = [
                'page' => $request->input('page', 1),
                'limit' => $request->input('limit', 10),
                'sort_column' => $request->input('sort_column', 'id'),
                'sort_order' => $request->input('sort_order', 'asc'),
            ];

            $result = $this->walletService->getAllWallets($filters, $pagination);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar carteiras',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateWalletRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $result = $this->walletService->updateWallet($id, $data);
            
            return response()->json([
                'message' => 'Carteira atualizada com sucesso',
                'wallet' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar carteira',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}