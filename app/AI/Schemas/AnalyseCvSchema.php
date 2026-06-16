<?php

namespace App\AI\Schemas;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;

#[Provider(Lab::Groq)]
#[Model('llama-3.3-70b-versatile')]
#[Temperature(0.3)]
#[Timeout(120)]
class AnalyseCvSchema implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): string
    {
        return "Tu es un expert RH spécialisé dans l'analyse de CV. "
            ."Analyse le CV fourni en le comparant à l'offre d'emploi."
            .'Retourne UNIQUEMENT un JSON conforme au schéma. Pas de texte en dehors du JSON.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'competences_extraites' => $schema->array(
                $schema->string()
            )->description('Compétences détectées dans le CV')->required(),

            'annees_experience' => $schema->integer()
                ->description("Années d'expérience estimées")->required(),

            'niveau_etudes' => $schema->string()
                ->description('Ex: Bac+2, Licence, Master')->required(),

            'langues' => $schema->array(
                $schema->string()
            )->description('Langues détectées')->required(),

            'matching_score' => $schema->integer()->min(0)->max(100)
                ->description('Score global de correspondance')->required(),

            'points_forts' => $schema->array(
                $schema->string()
            )->description('Atouts du candidat')->required(),

            'lacunes' => $schema->array(
                $schema->string()
            )->description("Faiblesses par rapport à l'offre")->required(),

            'competences_manquantes' => $schema->array(
                $schema->string()
            )->description('Compétences requises absentes du CV')->required(),

            'recommandation' => $schema->string()
                ->enum(['convoquer', 'attente', 'rejeter'])
                ->description('Décision IA finale')->required(),

            'justification' => $schema->string()
                ->description('Explication du score et de la recommandation')->required(),
        ];
    }
}
