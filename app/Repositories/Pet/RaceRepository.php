<?php

namespace App\Repositories\Pet;

use App\Models\Pet\Race;

class RaceRepository
{
    protected $model;

    public function __construct(Race $model)
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