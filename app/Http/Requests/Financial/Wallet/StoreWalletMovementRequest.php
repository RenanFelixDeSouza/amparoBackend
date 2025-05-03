<?php

namespace App\Http\Requests\Financial\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class StoreWalletMovementRequest extends FormRequest
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
     * Regras de validação para movimentações da carteira.
     *
     * @return array<string, string>
     */
    public function rules()
    {
        return [
            'wallet_id' => 'required|exists:wallets,id',
            'type' => 'required|in:entrada,saida',
            'value' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'comments' => 'nullable|string|max:255',
            'chart_account_id' => 'nullable|exists:chart_of_accounts,id'
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
            'wallet_id.required' => 'A carteira é obrigatória',
            'wallet_id.exists' => 'A carteira selecionada não existe',
            'type.required' => 'O tipo de movimentação é obrigatório',
            'type.in' => 'O tipo deve ser entrada ou saída',
            'value.required' => 'O valor é obrigatório',
            'value.numeric' => 'O valor deve ser um número',
            'value.min' => 'O valor deve ser maior que zero',
            'description.string' => 'A descrição deve ser um texto',
            'comments.string' => 'O comentário deve ser um texto',
            'comments.max' => 'O comentário não pode ter mais que 255 caracteres',
            'chart_account_id.exists' => 'O plano de contas selecionado não existe'
        ];
    }
}