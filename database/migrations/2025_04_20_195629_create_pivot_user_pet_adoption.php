<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pivot_user_pet_adoption', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['adopt', 'temporary_home']);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pet_id');
            $table->unsignedBigInteger('adoption_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pet_id')->references('id')->on('pets');
            $table->foreign('adoption_id')->references('id')->on('adoptions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_user_pet_adoption');
    }
};
