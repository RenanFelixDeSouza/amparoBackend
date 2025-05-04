<?php

namespace App\Http\Requests\Pet\Race;

use Illuminate\Foundation\Http\FormRequest;

class StoreRaceRequest extends FormRequest
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
     * Regras de validação para a criação de uma raça.
     *
     * @return array<string, string>
     */
    public function rules()
    {
        return [
            'description' => 'required|string|max:255',
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
            'description.required' => 'A descrição é obrigatória.',
            'description.string' => 'A descrição deve ser um texto.',
            'description.max' => 'A descrição não pode ter mais que 255 caracteres.',
        ];
    }
}