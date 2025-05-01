<?php

namespace App\Repositories\Wallet;

use App\Models\Wallet;

class WalletRepository
{
    protected $model;

    public function __construct(Wallet $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($wallet, array $data)
    {
        $wallet->update($data);
        return $wallet;
    }

    public function getUserWallets($userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function getUniqueBanks($userId)
    {
        return $this->model->select('bank_name')
            ->where('user_id', $userId)
            ->distinct()
            ->get()
            ->pluck('bank_name');
    }
}