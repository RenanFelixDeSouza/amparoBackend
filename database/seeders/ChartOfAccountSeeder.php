<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;

class ChartOfAccountSeeder extends Seeder
{
    public function run()
    {
        // Desativa as verificações de chave estrangeira temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Limpa a tabela
        ChartOfAccount::truncate();
        
        // Reativa as verificações de chave estrangeira
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}