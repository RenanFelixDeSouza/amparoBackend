<?php

namespace App\Models\Company;

use App\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'company_name',
        'fantasy_name',
        'cnpj',
        'ie',
        'ie_status',
        'im',
        'im_status',
        'email',
        'phone',
        'responsible_name',
        'address_id',
    ];

    /**
     * Relacionamento com o endereço (Address).
     *
     * @return BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

}
