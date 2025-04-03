<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletMovimmentsTable extends Migration
{
    public function up()
    {
        Schema::create('wallet_movimments', function (Blueprint $table) {
            $table->id();
            $table->decimal('value', 15, 2); 
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallet_movimments');
    }
}
