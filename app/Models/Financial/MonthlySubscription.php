<?php

namespace App\Models\Financial;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlySubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_movement_id',
        'subscription_plan_id',
        'start_date',
        'next_due_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'next_due_date' => 'date'
    ];

    public function walletMovement()
    {
        return $this->belongsTo(WalletMovement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
