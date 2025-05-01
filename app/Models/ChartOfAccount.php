<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
        'type',
        'account_code',
        'level',
        'path'
    ];

    protected $casts = [
        'type' => 'string',
        'level' => 'integer'
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('account_code');
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function walletMovements()
    {
        return $this->hasMany(WalletMovement::class);
    }

    public function getFullPathAttribute()
    {
        return $this->path ? explode('/', $this->path) : [];
    }

    public function isSynthetic()
    {
        return $this->type === 'synthetic';
    }

    public function isAnalytical()
    {
        return $this->type === 'analytical';
    }
}
