<?php

namespace App\Models\Financial;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'type',
        'value',
        'user_id',
        'comments',
        'chart_of_account_id'
    ];

    protected $casts = [
        'value' => 'decimal:2'
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashFlow()
    {
        return $this->hasOne(CashFlow::class);
    }

    public function monthlySubscription()
    {
        return $this->hasOne(MonthlySubscription::class);
    }
}