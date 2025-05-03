<?php

namespace App\Repositories\Financial;

use App\Models\Financial\Wallet;

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

    public function getUserWallets($userId, array $pagination = null)
    {
        $query = Wallet::where('user_id', $userId);

        if ($pagination) {
            return $query->orderBy($pagination['sort_by'], $pagination['sort_order'])
                        ->paginate($pagination['limit'], ['*'], 'page', $pagination['page']);
        }

        return $query->get();
    }

    public function getAll(array $filters, array $pagination)
    {
        $query = Wallet::query();

        if (!empty($filters['bank_name'])) {
            $query->where('bank_name', 'like', '%' . $filters['bank_name'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('account_type', $filters['type']);
        }

        return $query->orderBy($pagination['sort_by'], $pagination['sort_order'])
                    ->paginate($pagination['limit'], ['*'], 'page', $pagination['page']);
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