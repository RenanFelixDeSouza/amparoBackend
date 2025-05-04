<?php

namespace App\Services\Financial;

use App\Repositories\Financial\WalletMovementRepository;
use Illuminate\Support\Facades\DB;

class WalletMovementService
{
    protected $movementRepository;

    public function __construct(WalletMovementRepository $movementRepository)
    {
        $this->movementRepository = $movementRepository;
    }

    public function createMovement(array $data)
    {
        try {
            DB::beginTransaction();

            $wallet = $this->movementRepository->findWallet($data['wallet_id']);
            if (!$wallet) {
                throw new \Exception('Carteira não encontrada');
            }
            
            $data['user_id'] = auth()->user()->id;
            $movement = $this->movementRepository->create($data);

            $newBalance = $wallet->total_value + ($data['type'] === 'entrada' ? $data['value'] : -$data['value']);
            $this->movementRepository->updateWalletBalance($wallet, $newBalance);

            DB::commit();

            return [
                'message' => 'Movimento criado com sucesso',
                'movement' => $movement,
                'new_balance' => $newBalance
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao criar movimento: ' . $e->getMessage());
        }
    }

    public function getMovements($walletId)
    {
        try {
            $movements = $this->movementRepository->getWalletMovements($walletId);
            
            return [
                'movements' => $movements,
                'summary' => [
                    'total_entries' => $movements->where('type', 'entrada')->sum('value'),
                    'total_exits' => $movements->where('type', 'saida')->sum('value'),
                    'count' => $movements->count()
                ]
            ];
        } catch (\Exception $e) {
            throw new \Exception('Erro ao buscar movimentos: ' . $e->getMessage());
        }
    }

    public function listAllMovements(array $filters, array $pagination)
    {
        try {
            $result = $this->movementRepository->getAllMovements($filters, $pagination);
            $movements = $result['movements'];

            return [
                'movements' => $movements,
                'summary' => [
                    'total' => $movements->total(),
                    'per_page' => $movements->perPage(),
                    'current_page' => $movements->currentPage(),
                    'last_page' => $movements->lastPage(),
                    'total_entries' => $movements->where('type', 'entrada')->sum('value'),
                    'total_exits' => $movements->where('type', 'saida')->sum('value')
                ]
            ];
        } catch (\Exception $e) {
            throw new \Exception('Erro ao listar movimentações: ' . $e->getMessage());
        }
    }
}