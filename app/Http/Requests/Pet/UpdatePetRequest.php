<?php

namespace App\Http\Requests\Pet;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'specie' => 'required|string',
            'specie_id' => 'required|exists:species,id',
            'race' => 'required|string',
            'race_id' => 'required|exists:races,id',
            'birth_date' => 'nullable|date',
            'is_castrated' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'specie.required' => 'A espécie é obrigatória',
            'specie_id.required' => 'O ID da espécie é obrigatório',
            'specie_id.exists' => 'A espécie selecionada não existe',
            'race.required' => 'A raça é obrigatória',
            'race_id.required' => 'O ID da raça é obrigatório',
            'race_id.exists' => 'A raça selecionada não existe',
            'is_castrated.required' => 'O campo castrado é obrigatório',
            'is_castrated.boolean' => 'O campo castrado deve ser verdadeiro ou falso'
        ];
    }
}