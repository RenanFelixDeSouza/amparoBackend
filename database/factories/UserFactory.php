<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => 'Renan',
            'user_name' => 'renan',
            'email' => 'renanfdev@gmail.com',
            'password' => bcrypt('123'), // Senha fixa
            'type_user_id' => 1, // Substituir pelo ID de um tipo de usuário válido
            'address_id' => null, // Substituir pelo ID de um endereço válido, se necessário
        ];
    }
}
