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

    public function create(array $data)
    {
        $data['created_by'] = auth()->user()->id;
        return $this->model->create($data);
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