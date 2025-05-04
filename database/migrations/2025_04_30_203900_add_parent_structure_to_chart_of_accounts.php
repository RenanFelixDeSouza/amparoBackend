<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentStructureToChartOfAccounts extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            // Coluna para referenciar o pai (self-referencing)
            $table->unsignedBigInteger('parent_id')->nullable()->after('user_id');
            // Código contábil (exemplo: 1.1.01.001)
            $table->string('account_code')->unique()->after('name');
            // Nível hierárquico (1, 2, 3, etc)
            $table->integer('level')->default(1)->after('account_code');
            // Path materializado para facilitar consultas hierárquicas
            $table->string('path')->nullable()->after('level');
            
            // Constraint de foreign key para self-referencing
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('chart_of_accounts')
                  ->onDelete('restrict'); // Impede deleção se houver filhos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'account_code', 'level', 'path']);
        });
    }
}