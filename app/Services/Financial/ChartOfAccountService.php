<?php

namespace App\Services\Financial;

use App\Repositories\Financial\ChartOfAccountRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Financial\ChartOfAccount;

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

            $account = $this->repository->findById($id);

            if (isset($data['type'])) {
                // Validação de tipo
                if ($data['type'] === 'analytical' && $account->children()->exists()) {
                    throw new \Exception('Contas analíticas não podem ter filhos');
                }
                if ($data['type'] !== $account->type && $data['type'] === 'analytical' && $account->children()->exists()) {
                    throw new \Exception('Não é possível mudar para tipo analítico pois a conta possui filhos');
                }
            }

            // Validação de nível máximo de profundidade (exemplo: máximo 5 níveis)
            if (isset($data['parent_id']) && $data['parent_id'] !== $account->parent_id) {
                $newParent = $this->repository->findById($data['parent_id']);
                if ($newParent->level >= 5) {
                    throw new \Exception('Limite máximo de níveis hierárquicos atingido');
                }

                // Validação de ciclos na hierarquia
                if ($this->wouldCreateCycle($account->id, $data['parent_id'])) {
                    throw new \Exception('A mudança criaria um ciclo na hierarquia');
                }

                // Validação de tipo do pai
                if ($newParent->type === 'analytical') {
                    throw new \Exception('Contas analíticas não podem ter filhos');
                }
            }

            $account = $this->repository->update($id, $data);

            DB::commit();
            return [
                'message' => 'Conta atualizada com sucesso',
                'account' => $account
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao atualizar conta: ' . $e->getMessage());
        }
    }

    protected function wouldCreateCycle($accountId, $newParentId)
    {
        $parent = $this->repository->findById($newParentId);
        
        // Verifica se o novo pai é descendente da conta atual
        while ($parent) {
            if ($parent->id === $accountId) {
                return true;
            }
            $parent = $parent->parent;
        }
        
        return false;
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
            $page = $pagination['page'] ?? 1;
            $limit = $pagination['limit'] ?? 10;
            $sortBy = $pagination['sort_by'] ?? 'created_at';
            $sortOrder = $pagination['sort_order'] ?? 'desc';

            $accounts = $this->repository->getAllAccounts($filters, [
                'page' => $page,
                'limit' => $limit,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder
            ]);

            return [
                'accounts' => $accounts,
                'summary' => [
                    'total' => $accounts->total(),
                    'per_page' => $accounts->perPage(),
                    'current_page' => $accounts->currentPage(),
                    'last_page' => $accounts->lastPage(),
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

    public function getAccountById($id)
    {
        return $this->repository->findById($id);
    }

    public function getDirectChildren($id)
    {
        try {
            return $this->repository->getDirectChildren($id);
        } catch (\Exception $e) {
            throw new \Exception('Erro ao buscar contas filhas: ' . $e->getMessage());
        }
    }

    public function getParentHierarchy($id)
    {
        try {
            return $this->repository->getParentHierarchy($id);
        } catch (\Exception $e) {
            throw new \Exception('Erro ao buscar hierarquia de pais: ' . $e->getMessage());
        }
    }
}