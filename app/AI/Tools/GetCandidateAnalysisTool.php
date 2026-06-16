<?php

namespace App\Ai\Tools;

use App\Models\Analyse;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetCandidateAnalysisTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Récupère l\'analyse complète d\'un candidat depuis la base de données : score, compétences, points forts, lacunes, recommandation.';
    }

    public function handle(Request $request): Stringable|string
    {
        $analyse = Analyse::where('candidat_id', $request['candidat_id'])
            ->with('candidat')
            ->first();

        if (! $analyse) {
            return "Aucune analyse trouvée pour le candidat avec l'ID {$request['candidat_id']}.";
        }

        $candidat = $analyse->candidat;

        return json_encode([
            'candidat_nom' => $candidat->nom,
            'matching_score' => $analyse->matching_score,
            'competences_extraites' => $analyse->competences_extraites,
            'annees_experience' => $analyse->annees_experience,
            'niveau_etudes' => $analyse->niveau_etudes,
            'langues' => $analyse->langues,
            'points_forts' => $analyse->points_forts,
            'lacunes' => $analyse->lacunes,
            'competences_manquantes' => $analyse->competences_manquantes,
            'recommandation' => $analyse->recommandation?->value,
            'justification' => $analyse->justification,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'candidat_id' => $schema->integer()
                ->description("ID du candidat dont on veut l'analyse")
                ->required(),
        ];
    }
}
