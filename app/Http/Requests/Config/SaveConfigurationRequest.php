<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;

class SaveConfigurationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'monthly_chart_id' => 'required',
            'monthly_chart_name' => 'required|string',
            'donation_chart_id' => 'required',
            'donation_chart_name' => 'required|string',
            'sponsorship_chart_id' => 'required',
            'sponsorship_chart_name' => 'required|string',
            'default_wallet_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'monthly_chart_id.required' => 'O ID do plano de contas de mensalidade é obrigatório',
            'monthly_chart_name.required' => 'O nome do plano de contas de mensalidade é obrigatório',
            'donation_chart_id.required' => 'O ID do plano de contas de doação é obrigatório',
            'donation_chart_name.required' => 'O nome do plano de contas de doação é obrigatório',
            'sponsorship_chart_id.required' => 'O ID do plano de contas de patrocínio é obrigatório',
            'sponsorship_chart_name.required' => 'O nome do plano de contas de patrocínio é obrigatório',
            'default_wallet_id.required' => 'O ID da carteira padrão é obrigatório'
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        
        return [
            [
                'module' => 'financial',
                'key_name' => 'monthly_chart_id',
                'value' => $validated['monthly_chart_id'],
                'description' => 'ID do plano de contas de mensalidade'
            ],
            [
                'module' => 'financial',
                'key_name' => 'monthly_chart_name',
                'value' => $validated['monthly_chart_name'],
                'description' => 'Nome do plano de contas de mensalidade'
            ],
            [
                'module' => 'financial',
                'key_name' => 'donation_chart_id',
                'value' => $validated['donation_chart_id'],
                'description' => 'ID do plano de contas de doação'
            ],
            [
                'module' => 'financial',
                'key_name' => 'donation_chart_name',
                'value' => $validated['donation_chart_name'],
                'description' => 'Nome do plano de contas de doação'
            ],
            [
                'module' => 'financial',
                'key_name' => 'sponsorship_chart_id',
                'value' => $validated['sponsorship_chart_id'],
                'description' => 'ID do plano de contas de patrocínio'
            ],
            [
                'module' => 'financial',
                'key_name' => 'sponsorship_chart_name',
                'value' => $validated['sponsorship_chart_name'],
                'description' => 'Nome do plano de contas de patrocínio'
            ],
            [
                'module' => 'financial',
                'key_name' => 'default_wallet_id',
                'value' => $validated['default_wallet_id'],
                'description' => 'ID da carteira padrão'
            ]
        ];
    }
}