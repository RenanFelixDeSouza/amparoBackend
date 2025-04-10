<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
     * Regras de validação para criar uma empresa.
     *
     * @return array<string, string>
     */
    public function rules()
    {
        return [
            'company_name' => 'required|string|max:255',
            'fantasy_name' => 'required|string|max:255',
            'cnpj' => 'required|string|size:14|unique:companies,cnpj',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'ie' => 'nullable|string|max:50',
            'im' => 'nullable|string|max:50',
            'zip_code' => 'required|string|max:10',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'complement' => 'nullable|string|max:255',
            'district_name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
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
            'company_name.required' => 'O nome da empresa é obrigatório.',
            'fantasy_name.required' => 'O nome fantasia é obrigatório.',
            'cnpj.required' => 'O CNPJ é obrigatório.',
            'cnpj.size' => 'O CNPJ deve ter exatamente 14 caracteres.',
            'cnpj.unique' => 'O CNPJ já está cadastrado.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'address.zip_code.required' => 'O CEP é obrigatório.',
            'address.street.required' => 'A rua é obrigatória.',
            'address.number.required' => 'O número é obrigatório.',
            'address.district_name.required' => 'O bairro é obrigatório.',
            'address.city_id.required' => 'A cidade é obrigatória.',
            'address.city_id.exists' => 'A cidade selecionada não existe.',
        ];
    }
}