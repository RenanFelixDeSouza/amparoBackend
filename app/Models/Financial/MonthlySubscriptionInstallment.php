<?php

namespace App\Models\Financial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlySubscriptionInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_subscription_id',
        'installment_number',
        'total_installments',
        'wallet_movement_id',
        'value',
        'due_date',
        'status'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'due_date' => 'date'
    ];

    public function monthlySubscription()
    {
        return $this->belongsTo(MonthlySubscription::class);
    }

    public function walletMovement()
    {
        return $this->belongsTo(WalletMovement::class);
    }
}