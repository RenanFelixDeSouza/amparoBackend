<?php

namespace Database\Seeders;

use App\Models\User\TypeUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeUserSeeder extends Seeder
{
    public function run()
    { {
            $types = [
                [
                    'id' => 1,
                    'description' => 'master'
                ],
                [
                    'id' => 2,
                    'description' => 'admin'
                ],
                [
                    'id' => 3,
                    'description' => 'user'
                ]
            ];

            foreach ($types as $type) {
                TypeUser::firstOrCreate(
                    ['id' => $type['id']],
                    ['description' => $type['description']]
                );
            }
        }
    }
}
