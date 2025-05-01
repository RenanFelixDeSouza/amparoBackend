<?php

namespace App\Services\Wallet;

use App\Models\WalletMovement;
use App\Repositories\Wallet\WalletMovementRepository;
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

    public function listAllMovements(array $filters)
    {
        $query = WalletMovement::query();

        if (!empty($filters['wallet_id'])) {
            $query->where('wallet_id', $filters['wallet_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        $sortColumn = $filters['sort_column'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortColumn, $sortOrder);

        return $query->paginate(10);
    }
}