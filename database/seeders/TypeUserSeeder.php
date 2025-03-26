<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeUserSeeder extends Seeder
{
    public function run()
    {
        DB::table('type_users')->insert([
            'id' => 1,
            'description' => 'master', 
        ]);
        DB::table('type_users')->insert([
            'id' => 2,
            'description' => 'admin', 
        ]);
        DB::table('type_users')->insert([
            'id' => 3,
            'description' => 'basic', 
        ]);
    }
}
