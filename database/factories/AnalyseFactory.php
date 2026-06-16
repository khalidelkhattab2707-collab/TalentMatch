<?php

namespace Database\Factories;

use App\Models\Analyse;
use App\Models\Candidat;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnalyseFactory extends Factory
{
    protected $model = Analyse::class;

    public function definition(): array
    {
        return [
            'candidat_id' => Candidat::factory(),
            'competences_extraites' => fake()->randomElements(['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'MySQL', 'Docker', 'Git'], fake()->numberBetween(2, 5)),
            'annees_experience' => fake()->numberBetween(0, 15),
            'niveau_etudes' => fake()->randomElement(['Bac', 'Bac+2', 'Bac+3', 'Bac+5', 'Master', 'Doctorat']),
            'langues' => fake()->randomElements(['Français', 'Anglais', 'Arabe', 'Espagnol', 'Allemand'], fake()->numberBetween(1, 3)),
            'matching_score' => fake()->numberBetween(30, 100),
            'points_forts' => fake()->randomElements(['Travail en équipe', 'Autonomie', 'Résolution de problèmes', 'Communication', 'Leadership'], fake()->numberBetween(2, 3)),
            'lacunes' => fake()->randomElements(['Manque d\'expérience', 'Compétences techniques à améliorer', 'Absence de certification'], fake()->numberBetween(1, 2)),
            'competences_manquantes' => fake()->randomElements(['Docker', 'Kubernetes', 'AWS', 'React'], fake()->numberBetween(1, 2)),
            'recommandation' => fake()->randomElement(['convoquer', 'attente', 'rejeter']),
            'justification' => fake()->paragraph(),
        ];
    }
}
