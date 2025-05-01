<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class StoreWalletRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Regras de validação para criar uma carteira.
     *
     * @return array<string, string>
     */
    public function rules()
    {
        return [
            'bank_name' => 'required|string|max:255',
            'agency' => 'required|string|max:20',
            'account_number' => 'required|string|max:20',
            'account_type' => 'required|in:corrente,poupanca,investimento',
            'description' => 'nullable|string|max:255',
            'balance' => 'required|numeric|min:0',
        ];
    }

    /**
     * Mensagens de erro personalizadas.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'bank_name.required' => 'O nome do banco é obrigatório',
            'bank_name.string' => 'O nome do banco deve ser um texto',
            'agency.required' => 'A agência é obrigatória',
            'agency.string' => 'A agência deve ser um texto',
            'account_number.required' => 'O número da conta é obrigatório',
            'account_number.string' => 'O número da conta deve ser um texto',
            'account_type.required' => 'O tipo de conta é obrigatório',
            'account_type.in' => 'O tipo de conta deve ser: corrente, poupança ou investimento',
            'description.string' => 'A descrição deve ser um texto'
        ];
    }
}