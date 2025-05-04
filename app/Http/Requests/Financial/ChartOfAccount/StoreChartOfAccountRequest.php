<?php

namespace App\Http\Requests\Financial\ChartOfAccount;

use App\Models\Financial\ChartOfAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreChartOfAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:synthetic,analytical',
            'account_code' => [
                'required',
                'string',
                'unique:chart_of_accounts,account_code',
                'regex:/^\d+(\.\d+)*$/',
                function ($attribute, $value, $fail) {
                    if ($this->input('parent_id')) {
                        $parent = ChartOfAccount::find($this->input('parent_id'));
                        if ($parent && !str_starts_with($value, $parent->account_code . '.')) {
                            $fail('O código da conta deve começar com o código da conta pai');
                        }
                    } else {
                        // Verifica se é uma conta pai de primeiro nível (deve ter formato X.0.0)
                        if (!preg_match('/^\d+\.0\.0$/', $value)) {
                            $fail('Contas de primeiro nível devem ter o formato X.0.0');
                        }
                    }
                }
            ],
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'level' => 'nullable|integer|min:1',
            'path' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome da conta é obrigatório',
            'name.max' => 'O nome da conta não pode ter mais que 255 caracteres',
            'type.required' => 'O tipo da conta é obrigatório',
            'type.in' => 'O tipo da conta deve ser sintética ou analítica',
            'account_code.required' => 'O código da conta é obrigatório',
            'account_code.unique' => 'Este código de conta já está em uso',
            'parent_id.exists' => 'A conta pai informada não existe',
            'level.integer' => 'O nível deve ser um número inteiro',
            'level.min' => 'O nível deve ser maior que zero',
        ];
    }
}