<?php

namespace App\Repositories\Pet;

use App\Models\Pet\Pet;
use DB;

class PetRepository
{

    public function getAll(array $filters, array $pagination)
    {
        $allowedSortColumns = [
            'pets.name',
            'races.description',
            'species.description',
            'pets.created_at',
        ];

        // Valida a coluna de ordenação
        $sortColumn = in_array($pagination['sort_column'], $allowedSortColumns)
            ? $pagination['sort_column']
            : 'pets.name';

        return Pet::query()
            ->leftJoin('races', 'pets.race_id', '=', 'races.id')
            ->leftJoin('species', 'pets.specie_id', '=', 'species.id')
            ->when($filters['name'], function ($query, $name) {
                $query->where('pets.name', 'like', '%' . $name . '%');
            })
            ->when($filters['specie'], function ($query, $specie) {
                $query->where('species.description', 'like', '%' . $specie . '%');
            })
            ->when($filters['race'], function ($query, $race) {
                $query->where('races.description', 'like', '%' . $race . '%');
            })
            ->orderBy($sortColumn, $pagination['sort_order'])
            ->paginate($pagination['limit'], [
                'pets.*',
                'races.description as race_description',
                'species.description as specie_description'
            ], 'page', $pagination['page']);
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {

            if ($data['birth_date'] === "") {
                $data['birth_date'] = null;
            }

            $pet = Pet::create($data);
            DB::table('pivot_pet_users')->insert([
                'pet_id' => $pet->id,
                'user_id' => $data['user_id'],
                'action' => 'created',
            ]);
            DB::commit();
            return $pet;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();
        try {
            $pet = Pet::findOrFail($id);

            if ($data['birth_date'] === "") {
                $data['birth_date'] = null;
            }

            $pet->update($data);

            // Garante que temos um ID de usuário válido
            $userId = auth()->user()->id;
            if (!$userId) {
                throw new \Exception('Usuário não autenticado');
            }

            DB::table('pivot_pet_users')->insert([
                'pet_id' => $pet->id,
                'user_id' => $userId,
                'action' => 'updated',
            ]);

            DB::commit();
            return $pet;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function hasAdoption($petId)
    {
        return DB::table('adoptions')->where('pet_id', $petId)->exists();
    }
}