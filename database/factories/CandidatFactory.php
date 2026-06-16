<?php

namespace Database\Factories;

use App\Models\Offre;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'offre_id' => Offre::factory(),
            'nom' => fake()->name(),
            'cv_texte' => fake()->paragraphs(5, true),
            'statut_job' => 'en_attente',
        ];
    }
}
