<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_subscription_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monthly_subscription_id')
                ->constrained('monthly_subscriptions')
                ->onDelete('cascade')
                ->name('fk_monthly_subscription');
            $table->integer('installment_number');
            $table->integer('total_installments');
            $table->foreignId('wallet_movement_id')
                ->nullable()
                ->constrained('wallet_movements')
                ->onDelete('set null')
                ->name('fk_wallet_movement');
            $table->decimal('value', 10, 2);
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_subscription_installments');
    }
};