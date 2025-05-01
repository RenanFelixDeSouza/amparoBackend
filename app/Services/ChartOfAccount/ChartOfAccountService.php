<?php

namespace App\Services\ChartOfAccount;

use App\Repositories\ChartOfAccount\ChartOfAccountRepository;
use Illuminate\Support\Facades\DB;
use App\Models\ChartOfAccount;

class ChartOfAccountService
{
    protected $repository;

    public function __construct(ChartOfAccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createAccount(array $data)
    {
        try {
            DB::beginTransaction();

            // Validação específica para o código da conta
            $this->repository->validateAccountCode(
                $data['account_code'], 
                $data['parent_id'] ?? null
            );

            $data['user_id'] = auth()->user()->id;
            $account = $this->repository->create($data);

            DB::commit();

            return [
                'message' => 'Conta criada com sucesso',
                'account' => $account
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao criar conta: ' . $e->getMessage());
        }
    }

    public function updateAccount($id, array $data)
    {
        try {
            DB::beginTransaction();

            if (isset($data['account_code'])) {
                $this->validateAccountCode($data['account_code'], $id, $data['parent_id'] ?? null);
            }

            if (isset($data['type'])) {
                $this->validateAccountType($data);
            }

            $account = $this->repository->update($id, $data);

            DB::commit();
            return ['message' => 'Conta atualizada com sucesso', 'account' => $account];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao atualizar conta: ' . $e->getMessage());
        }
    }

    protected function validateAccountCode($code, $excludeId = null, $parentId = null)
    {
        if (!preg_match('/^\d+(\.\d+)*$/', $code)) {
            throw new \Exception('Formato de código inválido');
        }

        $query = ChartOfAccount::where('account_code', $code);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        if ($query->exists()) {
            throw new \Exception('Código já existe');
        }

        if ($parentId) {
            $parent = ChartOfAccount::find($parentId);
            if (!str_starts_with($code, $parent->account_code . '.')) {
                throw new \Exception('Código deve seguir a hierarquia do pai');
            }
        }
    }

    protected function validateAccountType($data)
    {
        if (!in_array($data['type'], ['analytical', 'synthetic'])) {
            throw new \Exception('Tipo de conta inválido');
        }
    }

    public function getAllAccounts(array $filters, array $pagination)
    {
        try {
            $accounts = $this->repository->getAllAccounts($filters, $pagination);
            return [
                'accounts' => $accounts,
                'summary' => [
                    'total' => $accounts->total(),
                    'synthetic_count' => $accounts->where('type', 'synthetic')->count(),
                    'analytical_count' => $accounts->where('type', 'analytical')->count()
                ]
            ];
        } catch (\Exception $e) {
            throw new \Exception('Erro ao buscar contas: ' . $e->getMessage());
        }
    }

    public function getHierarchy()
    {
        try {
            return $this->repository->getHierarchy();
        } catch (\Exception $e) {
            throw new \Exception('Erro ao buscar hierarquia: ' . $e->getMessage());
        }
    }
}