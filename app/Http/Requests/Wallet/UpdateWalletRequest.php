<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWalletRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'bank_name' => 'sometimes|string|max:255',
            'agency' => 'sometimes|string|max:20',
            'account_number' => 'sometimes|string|max:50',
            'account_type' => 'sometimes|string|in:corrente,poupanca',
            'description' => 'sometimes|nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'bank_name.string' => 'O nome do banco deve ser um texto',
            'agency.string' => 'A agência deve ser um texto',
            'account_number.string' => 'O número da conta deve ser um texto',
            'account_type.in' => 'O tipo de conta deve ser corrente ou poupança',
            'description.string' => 'A descrição deve ser um texto'
        ];
    }
}