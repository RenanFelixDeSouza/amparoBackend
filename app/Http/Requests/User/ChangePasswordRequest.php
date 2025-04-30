<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Regras de validação
     *
     * @return array<string, string>
     */
    public function rules()
    {
        return [
            'new_password' => 'required|string|min:3|confirmed',
            'admin_password' => 'required|string',
        ];
    }

    /**
     * Mensagens de erro personalizadas
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'new_password.required' => 'A nova senha é obrigatória',
            'new_password.min' => 'A nova senha deve ter no mínimo 6 caracteres',
            'new_password.confirmed' => 'As senhas não conferem',
            'admin_password.required' => 'A senha atual é obrigatória'
        ];
    }
}