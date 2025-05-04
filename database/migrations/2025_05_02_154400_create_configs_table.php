<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->string('module', 50);
            $table->string('key_name', 100);
            $table->text('value')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Inserindo dados iniciais
        DB::table('configs')->insert([
            [
                'module' => 'financial',
                'key_name' => 'chart_account_banking',
                'value' => null,
                'description' => 'Plano de Contas padrão na criação de uma nova Movimentação Bancária'
            ],
            [
                'module' => 'financial',
                'key_name' => 'chart_account_monthly',
                'value' => null,
                'description' => 'Plano de Contas padrão para Controle de Mensalidades'
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('configs');
    }
}