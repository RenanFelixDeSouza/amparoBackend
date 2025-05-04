<?php

namespace App\Http\Requests\Financial\ChartOfAccount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChartOfAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $accountId = $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                Rule::unique('chart_of_accounts')->where(function ($query) {
                    return $query->where('parent_id', $this->input('parent_id'));
                })->ignore($accountId)
            ],
            'type' => ['required', Rule::in(['analytical', 'synthetic'])],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres',
            'name.max' => 'O nome deve ter no máximo 100 caracteres',
            'name.unique' => 'Já existe uma conta com este nome no mesmo nível',
            'type.required' => 'O tipo é obrigatório',
            'type.in' => 'O tipo deve ser analytical ou synthetic',
            'description.max' => 'A descrição deve ter no máximo 255 caracteres'
        ];
    }
}