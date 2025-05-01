<?php

namespace App\Repositories\Wallet;

use App\Models\WalletMovement;
use App\Models\Wallet;

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
            ->with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findWallet($walletId)
    {
        return $this->walletModel->findOrFail($walletId);
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
}