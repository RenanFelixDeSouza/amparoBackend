<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotMedicalAppointmentPetTable extends Migration
{
    public function up()
    {
        Schema::create('pivot_medical_appointment_pet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');
            $table->foreignId('medical_appointment_id')->constrained('medical_appointments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pivot_medical_appointment_pet');
    }
}
