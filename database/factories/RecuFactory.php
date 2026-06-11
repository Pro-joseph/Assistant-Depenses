<?php

namespace Database\Factories;

use App\Models\Recu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecuFactory extends Factory
{
    protected $model = Recu::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'texte_brut' => fake()->paragraph(),
            'statut' => 'en_attente',
        ];
    }

    public function traite(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'traite',
        ]);
    }

    public function echoue(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'echoue',
        ]);
    }
}
