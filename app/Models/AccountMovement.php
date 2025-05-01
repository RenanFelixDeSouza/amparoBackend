<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'chart_of_account_id',
        'user_id',
        'value',
        'description',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
        'value' => 'decimal:2'
    ];

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}