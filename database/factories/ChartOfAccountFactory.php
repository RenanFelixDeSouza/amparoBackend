<?php

namespace Database\Factories;

use App\Models\Financial\ChartOfAccount;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChartOfAccountFactory extends Factory
{
    protected $model = ChartOfAccount::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['synthetic', 'analytical']),
            'account_code' => function () {
                // Gera códigos no formato X.0.0 para contas pai
                return $this->faker->numberBetween(1, 9) . '.0.0';
            },
            'level' => 1,
            'path' => null,
            'parent_id' => null
        ];
    }

    // Estado para contas filhas
    public function child(ChartOfAccount $parent)
    {
        return $this->state(function (array $attributes) use ($parent) {
            $childCode = $parent->account_code . '.' . $this->faker->numberBetween(1, 9);
            
            return [
                'parent_id' => $parent->id,
                'account_code' => $childCode,
                'level' => $parent->level + 1,
                'path' => $parent->path 
                    ? $parent->path . '/' . $parent->id
                    : (string)$parent->id
            ];
        });
    }
}