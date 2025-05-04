<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wallet_movements', function (Blueprint $table) {
            $table->enum('type', ['entrada', 'saida'])->after('wallet_id');
        });
    }

    public function down()
    {
        Schema::table('wallet_movements', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};