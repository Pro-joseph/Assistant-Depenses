<?php

namespace Database\Factories;

use App\Models\Depense;
use App\Models\Recu;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepenseFactory extends Factory
{
    protected $model = Depense::class;

    public function definition(): array
    {
        return [
            'recu_id' => Recu::factory(),
            'libelle' => fake()->word(),
            'quantite' => fake()->numberBetween(1, 10),
            'prix_unitaire' => fake()->randomFloat(2, 1, 100),
            'categorie' => fake()->randomElement(['alimentaire', 'boissons', 'hygiene', 'entretien', 'autre']),
        ];
    }
}
