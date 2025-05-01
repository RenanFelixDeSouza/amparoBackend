<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'type',
        'value',
        'category_id',
        'user_id',
        'comments' 
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
}