<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'type',
        'bank_name',
        'agency',
        'account_number',
        'account_type',
        'total_value',
        'description'
    ];

    protected $casts = [
        'total_value' => 'decimal:2'
    ];

    public function movements()
    {
        return $this->hasMany(WalletMovement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}