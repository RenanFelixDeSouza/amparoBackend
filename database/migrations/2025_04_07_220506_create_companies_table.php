<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('fantasy_name');
            $table->string('cnpj', 14)->unique();
            $table->string('ie')->nullable();
            $table->string('ie_status');
            $table->string('im')->nullable();
            $table->string('im_status');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('responsible_name')->nullable();
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}