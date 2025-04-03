<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->string('color', 45);
            $table->boolean('is_castrated');
            $table->string('age', 45);
            $table->foreignId('race_id')->constrained('races')->onDelete('cascade');
            $table->foreignId('specie_id')->constrained('species')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pets');
    }
}
