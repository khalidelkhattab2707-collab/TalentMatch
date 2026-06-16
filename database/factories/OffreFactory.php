<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OffreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'titre' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'competences_requises' => fake()->randomElements(['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'MySQL', 'Docker', 'Git', 'REST API'], fake()->numberBetween(1, 5)),
            'experience_minimum' => fake()->numberBetween(0, 10),
            'statut' => 'active',
        ];
    }
}
