<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wallet_movements', function (Blueprint $table) {
            // Adiciona as novas colunas
            if (!Schema::hasColumn('wallet_movements', 'movement_type')) {
                $table->string('movement_type')->nullable()->after('type');
            }

            if (!Schema::hasColumn('wallet_movements', 'status')) {
                $table->enum('status', ['pending', 'confirmed', 'cancelled'])
                    ->default('pending');
            }

            if (!Schema::hasColumn('wallet_movements', 'date')) {
                $table->timestamp('date')->nullable()->after('status');
            }

            if (!Schema::hasColumn('wallet_movements', 'description')) {
                $table->string('description')->nullable()->after('movement_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_movements', function (Blueprint $table) {
            // Remove as colunas na ordem inversa
            $table->dropColumn([
                'date',
                'status',
                'description',
                'movement_type'
            ]);
        });
    }
};
