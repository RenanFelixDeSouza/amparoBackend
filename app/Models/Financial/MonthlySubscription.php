<?php

namespace App\Models\Financial;

use App\Models\User\User;
use App\Models\Financial\SubscriptionPlan;
use App\Models\Financial\WalletMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlySubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'start_date',
        'next_due_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'next_due_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function walletMovement()
    {
        return $this->belongsTo(WalletMovement::class, 'wallet_movement_id');
    }

    public function installments()
    {
        return $this->hasMany(MonthlySubscriptionInstallment::class);
    }
}
