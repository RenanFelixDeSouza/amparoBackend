<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPacksTable extends Migration
{
    public function up()
    {
        Schema::create('product_packs', function (Blueprint $table) {
            $table->id();
            $table->string('size', 255)->nullable();
            $table->string('amount', 45);
            $table->string('expiration_date', 45);
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_packs');
    }
}
