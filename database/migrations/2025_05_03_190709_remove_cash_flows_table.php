<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Primeiro remove as chaves estrangeiras que apontam para cash_flows
        Schema::table('wallet_movements', function (Blueprint $table) {
            // Remove relacionamentos se existirem
            if (Schema::hasColumn('wallet_movements', 'cash_flow_id')) {
                $table->dropForeign(['cash_flow_id']);
                $table->dropColumn('cash_flow_id');
            }
        });

        // Remove a tabela cash_flows
        Schema::dropIfExists('cash_flows');
    }

    public function down()
    {
        // Recria a tabela cash_flows caso precise fazer rollback
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->enum('flow_type', ['inflow', 'outflow']);
            $table->string('description');
            $table->decimal('value', 10, 2);
            $table->datetime('date');
            $table->enum('status', ['pending', 'confirmed', 'cancelled']);
            $table->text('comments')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('wallet_movement_id')->nullable()->constrained('wallet_movements');
            $table->timestamps();
        });
    }
};