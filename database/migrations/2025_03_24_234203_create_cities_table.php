<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10);
            $table->string('name', 255);
            $table->string('federative_unit', 10);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
