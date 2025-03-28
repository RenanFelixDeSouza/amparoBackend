<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'zip_code' => 'sometimes|string|max:10',
            'street' => 'sometimes|string|max:255',
            'number' => 'sometimes|string|max:10',
            'complement' => 'nullable|string',
            'district_name' => 'sometimes|string|max:255',
            'city_id' => 'sometimes|integer|exists:cities,id',
            'photo' => 'required|image|mimes:png|max:2048',
        ];
    }
}
