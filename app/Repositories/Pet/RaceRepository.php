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

    public function getAll(array $filters, array $pagination)
    {
        $allowedSortColumns = [
            'races.description',
            'races.created_at',
        ];

        // Valida a coluna de ordenação
        $sortColumn = in_array($pagination['sort_column'], $allowedSortColumns)
            ? $pagination['sort_column']
            : 'races.description';

        return $this->model->query()
            ->when($filters['search'], function ($query, $search) {
                $query->where('description', 'like', '%' . $search . '%');
            })
            ->when($filters['description'], function ($query, $description) {
                $query->where('description', 'like', '%' . $description . '%');
            })
            ->orderBy($sortColumn, $pagination['sort_order'])
            ->paginate($pagination['limit'], ['*'], 'page', $pagination['page']);
    }
}