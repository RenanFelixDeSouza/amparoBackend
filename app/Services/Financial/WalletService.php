<?php

namespace App\Services\Financial;

use App\Repositories\Financial\WalletRepository;
use App\Models\Financial\Wallet;
use App\Models\Financial\CashFlow;
use App\Models\Financial\WalletMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class WalletService
{
    protected $walletRepository;

    public function __construct(WalletRepository $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    public function createWallet(array $data)
    {
        try {
            DB::beginTransaction();

            $data['user_id'] = auth()->user()->id;
            $data['total_value'] = $data['balance'];
            $wallet = $this->walletRepository->create($data);

            // Se houver saldo inicial, criar movimentação
            if (isset($data['balance']) && $data['balance'] > 0) {
                // Criar movimento na carteira
                $movement = WalletMovement::create([
                    'wallet_id' => $wallet->id,
                    'type' => 'entrada',
                    'value' => $data['balance'],
                    'description' => 'Saldo inicial da carteira',
                    'user_id' => auth()->user()->id,
                    'comments' => 'Movimentação gerada automaticamente na criação da carteira'
                ]);

                // Criar registro no fluxo de caixa
                CashFlow::create([
                    'flow_type' => 'inflow',
                    'description' => 'Saldo inicial da carteira: ' . $wallet->bank_name,
                    'value' => $data['balance'],
                    'date' => now(),
                    'status' => 'confirmed',
                    'comments' => 'Movimentação de entrada avulsa referente à criação da carteira ' . $wallet->bank_name,
                    'user_id' => auth()->user()->id,
                    'wallet_movement_id' => $movement->id
                ]);
            }

            DB::commit();

            return [
                'message' => 'Carteira criada com sucesso',
                'wallet' => $wallet
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao criar carteira: ' . $e->getMessage());
        }
    }

    public function getBalance($walletId)
    {
        try {
            $wallet = $this->walletRepository->findById($walletId);
            return ['balance' => $wallet->total_value];
        } catch (\Exception $e) {
            throw new \Exception('Carteira não encontrada');
        }
    }

    public function listAll()
    {
        try {
            $userId = auth()->user()->id;

            $wallets = $this->walletRepository->getUserWallets($userId);
            $banks = $this->walletRepository->getUniqueBanks($userId);
            $totalBalance = $wallets->sum('total_value');

            return [
                'wallets' => $wallets,
                'banks' => $banks,
                'total_balance' => $totalBalance,
                'summary' => [
                    'total_wallets' => $wallets->count(),
                    'total_banks' => $banks->count(),
                ]
            ];
        } catch (\Exception $e) {
            throw new \Exception('Erro ao buscar dados das carteiras: ' . $e->getMessage());
        }
    }

    public function getAllWallets(array $filters, array $pagination)
    {
        try {
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

            $wallets = $query->orderBy($pagination['sort_by'], $pagination['sort_order'])
                            ->paginate($pagination['limit'], ['*'], 'page', $pagination['page']);

            return [
                'wallets' => $wallets->items(),
                'summary' => [
                    'total' => $wallets->total(),
                    'per_page' => $wallets->perPage(),
                    'current_page' => $wallets->currentPage(),
                    'last_page' => $wallets->lastPage(),
                    'total_balance' => $wallets->sum('total_value')
                ]
            ];
        } catch (\Exception $e) {
            throw new \Exception('Erro ao buscar carteiras: ' . $e->getMessage());
        }
    }

    public function updateWallet($id, array $data)
    {
        try {
            DB::beginTransaction();

            $wallet = $this->walletRepository->findById($id);
            
            if (!$wallet) {
                throw new \Exception('Carteira não encontrada');
            }

            $wallet = $this->walletRepository->update($wallet, $data);

            DB::commit();

            return $wallet;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao atualizar carteira: ' . $e->getMessage());
        }
    }
}