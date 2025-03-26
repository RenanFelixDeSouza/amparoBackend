<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdoptionsTable extends Migration
{
    public function up()
    {
        Schema::create('adoptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_donor')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_donee')->constrained('users')->onDelete('cascade');
            $table->string('date', 45);
            $table->boolean('is_canceled')->default(false);
            $table->string('reason_canceled', 45)->nullable();
            $table->string('adoptioncol', 45)->nullable();
            $table->string('date_canceled', 45)->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('adoptions');
    }
}
