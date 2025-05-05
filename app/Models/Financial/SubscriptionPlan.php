<?php

namespace App\Models\Financial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'duration_months',
        'is_active',
        'description',
        'chart_of_account_id'
    ];

    protected $casts = [
        'value' => 'float',
        'duration_months' => 'integer',
        'is_active' => 'boolean'
    ];

    public function monthlySubscriptions()
    {
        return $this->hasMany(MonthlySubscription::class);
    }
}