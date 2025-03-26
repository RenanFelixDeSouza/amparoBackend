<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validação de requisições de autenticação
 * @package App\Http\Requests\Auth
 */
class AuthRequest extends FormRequest
{
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

    public function messages()
    {
        return [
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'Formato de e-mail inválido',
            'password.required' => 'A senha é obrigatória'
        ];
    }
}
