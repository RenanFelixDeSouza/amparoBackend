_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->enum('flow_type', ['inflow', 'outflow']);
            $table->string('description');
            $table->decimal('value', 10, 2);
            $table->datetime('date');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->text('comments')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('wallet_movement_id')->nullable()->constrained('wallet_movements');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cash_flows');
    }
};