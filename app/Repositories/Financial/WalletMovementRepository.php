<?php

namespace App\Repositories\Financial;

use App\Models\Financial\Wallet;
use App\Models\Financial\WalletMovement;


class WalletMovementRepository
{
    protected $model;
    protected $walletModel;

    public function __construct(WalletMovement $model, Wallet $walletModel)
    {
        $this->model = $model;
        $this->walletModel = $walletModel;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function getWalletMovements($walletId)
    {
        return $this->model->where('wallet_id', $walletId)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findWallet($walletId)
    {
        return Wallet::where('id', $walletId)->first();
    }

    public function updateWalletBalance(Wallet $wallet, $newBalance)
    {
        $wallet->total_value = $newBalance;
        return $wallet->save();
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($movement, array $data)
    {
        $movement->update($data);
        return $movement;
    }

    public function getAllMovements(array $filters, array $pagination)
    {
        $query = $this->model->with(['user',  'wallet']);

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

        $movements = $query->orderBy($pagination['sort_by'], $pagination['sort_order'])
                          ->paginate($pagination['limit'], ['*'], 'page', $pagination['page']);

        return [
            'movements' => $movements
        ];
    }
}