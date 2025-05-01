<?php

namespace App\Http\Requests\ChartOfAccount;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChartOfAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'sometimes|string|unique:chart_of_accounts,code,' . $this->id,
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:synthetic,analytical',
            'nature' => 'sometimes|in:debit,credit',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'status' => 'sometimes|boolean',
            'description' => 'nullable|string'
        ];
    }
}