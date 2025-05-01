<?php

namespace App\Repositories\ChartOfAccount;

use App\Models\ChartOfAccount;

class ChartOfAccountRepository
{
    protected $model;

    public function __construct(ChartOfAccount $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        if (isset($data['parent_id'])) {
            $parent = $this->findById($data['parent_id']);
            $data['level'] = $parent->level + 1;
            $data['path'] = $parent->path 
                ? $parent->path . '/' . $parent->id
                : (string)$parent->id;
        } else {
            // É uma conta pai de primeiro nível
            $data['level'] = 1;
            $data['path'] = null;
            
            // Garante que o código está no formato correto (X.0.0)
            if (!preg_match('/^\d+\.0\.0$/', $data['account_code'])) {
                throw new \Exception('Código de conta pai deve estar no formato X.0.0');
            }
        }

        return $this->model->create($data);
    }

    public function validateAccountCode($code, $parentId = null)
    {
        if ($parentId) {
            $parent = $this->findById($parentId);
            if (!str_starts_with($code, $parent->account_code . '.')) {
                throw new \Exception('O código da conta deve começar com o código da conta pai');
            }
        } else {
            // Verifica se já existe uma conta com o mesmo primeiro dígito
            $firstDigit = explode('.', $code)[0];
            $exists = $this->model
                ->whereRaw("SUBSTRING_INDEX(account_code, '.', 1) = ?", [$firstDigit])
                ->exists();
                
            if ($exists) {
                throw new \Exception("Já existe uma conta pai com o código {$firstDigit}");
            }
        }
    }

    public function update($id, array $data)
    {
        $account = $this->findById($id);
        
        if (isset($data['parent_id']) && $data['parent_id'] !== $account->parent_id) {
            $parent = $this->findById($data['parent_id']);
            $data['level'] = $parent->level + 1;
            $data['path'] = $parent->path 
                ? $parent->path . '/' . $parent->id
                : (string)$parent->id;
            
            // Atualiza o path de todos os filhos
            $this->updateChildrenPaths($account);
        }

        $account->update($data);
        return $account;
    }

    protected function updateChildrenPaths($account)
    {
        foreach ($account->children as $child) {
            $child->level = $account->level + 1;
            $child->path = $account->path 
                ? $account->path . '/' . $account->id
                : (string)$account->id;
            $child->save();
            
            $this->updateChildrenPaths($child);
        }
    }

    public function getAllAccounts(array $filters, array $pagination)
    {
        $query = $this->model->query();

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['parent_id'])) {
            $query->where('parent_id', $filters['parent_id']);
        }

        if (isset($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        return $query->with(['parent', 'children'])
            ->orderBy('account_code')
            ->paginate($pagination['limit']);
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getHierarchy()
    {
        return $this->model
            ->whereNull('parent_id')
            ->with('allChildren')
            ->orderBy('account_code')
            ->get();
    }
}