<?php

namespace App\Repositories\Pet;

use App\Models\Pet\Pet;
use DB;

class PetRepository
{
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            if($data['age_unit'] == 'meses'){
                $data['age'] *=  30;
            }
            if($data['is_age_unknown'] == 1) {
                $data['age'] = null;
            }
            $pet = Pet::create($data);
            DB::table('pivot_pet_users')->insert([
                'pet_id' => $pet->id,
                'user_id' => $data['user_id'],
                'action'=> 'created',
            ]);
            DB::commit();
            return $pet;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}