<?php

namespace App\Models\Financial;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'flow_type', // 'inflow' ou 'outflow'
        'description',
        'value',
        'date',
        'status', // 'pending', 'confirmed', 'cancelled'
        'comments',
        'user_id',
        'wallet_movement_id'
    ];

    protected $casts = [
        'date' => 'datetime',
        'value' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function walletMovement()
    {
        return $this->belongsTo(WalletMovement::class);
    }
}