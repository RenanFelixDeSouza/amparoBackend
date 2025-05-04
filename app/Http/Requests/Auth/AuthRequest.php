<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Regras de validação para autenticação
     * @return array<string, string>
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string'
        ];
    }

    /**
     * Mensagens de erro personalizadas
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'Formato de e-mail inválido',
            'password.required' => 'A senha é obrigatória'
        ];
    }
}
