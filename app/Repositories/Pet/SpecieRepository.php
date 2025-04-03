<?php

namespace App\Repositories\Pet;

use App\Models\Pet\Specie;

class SpecieRepository
{
    protected $model;

    public function __construct(Specie $model)
    {
        $this->model = $model;
    }

    public function getAll(string $search = '', int $limit = 5)
    {
        return $this->model->query()
            ->when($search, function ($query, $search) {
                $query->where('description', 'like', '%' . $search . '%');
            })
            ->paginate($limit);
    }
}