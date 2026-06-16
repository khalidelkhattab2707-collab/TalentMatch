<?php

namespace App\AI\Schemas;

use App\Exceptions\AnalyseIAException;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;

#[Provider(Lab::Groq)]
#[Model('llama-3.3-70b-versatile')]
#[Temperature(0.3)]
#[Timeout(120)]
class AnalyseCvSchema implements Agent
{
    use Promptable;

    public function instructions(): string
    {
        return "Tu es un expert RH spécialisé dans l'analyse de CV. "
            ."Analyse le CV fourni en le comparant à l'offre d'emploi."
            .'Retourne UNIQUEMENT un JSON valide avec les champs suivants : '
            .'competences_extraites (array de strings), '
            .'annees_experience (integer), '
            .'niveau_etudes (string), '
            .'langues (array de strings), '
            .'matching_score (integer entre 0 et 100), '
            .'points_forts (array de strings), '
            .'lacunes (array de strings), '
            .'competences_manquantes (array de strings), '
            .'recommandation (string : "convoquer", "attente", ou "rejeter"), '
            .'justification (string). '
            .'Pas de texte en dehors du JSON.';
    }

    public static function validateResponse(array $data): array
    {
        $requiredFields = [
            'competences_extraites', 'annees_experience', 'niveau_etudes',
            'langues', 'matching_score', 'points_forts', 'lacunes',
            'competences_manquantes', 'recommandation', 'justification',
        ];

        foreach ($requiredFields as $field) {
            if (! array_key_exists($field, $data)) {
                throw new AnalyseIAException(
                    "Champ manquant dans la réponse IA : {$field}"
                );
            }
        }

        if (! is_int($data['matching_score']) || $data['matching_score'] < 0 || $data['matching_score'] > 100) {
            throw new AnalyseIAException(
                'matching_score doit être un entier entre 0 et 100, reçu : '.json_encode($data['matching_score'])
            );
        }

        if (! is_array($data['competences_extraites'])) {
            throw new AnalyseIAException('competences_extraites doit être un array');
        }

        if (! is_array($data['langues'])) {
            throw new AnalyseIAException('langues doit être un array');
        }

        if (! is_array($data['points_forts'])) {
            throw new AnalyseIAException('points_forts doit être un array');
        }

        if (! is_array($data['lacunes'])) {
            throw new AnalyseIAException('lacunes doit être un array');
        }

        if (! is_array($data['competences_manquantes'])) {
            throw new AnalyseIAException('competences_manquantes doit être un array');
        }

        if (! is_string($data['niveau_etudes']) || $data['niveau_etudes'] === '') {
            throw new AnalyseIAException('niveau_etudes doit être une chaîne non vide');
        }

        if (! is_string($data['justification']) || $data['justification'] === '') {
            throw new AnalyseIAException('justification doit être une chaîne non vide');
        }

        if (! is_int($data['annees_experience']) || $data['annees_experience'] < 0) {
            throw new AnalyseIAException('annees_experience doit être un entier positif');
        }

        if (! is_string($data['recommandation']) || ! in_array($data['recommandation'], ['convoquer', 'attente', 'rejeter'], true)) {
            throw new AnalyseIAException(
                'recommandation doit être "convoquer", "attente" ou "rejeter", reçu : '.json_encode($data['recommandation'])
            );
        }

        return $data;
    }
}
