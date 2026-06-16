<?php

namespace App\Ai\Tools;

use App\Models\Analyse;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class CompareCandidatesTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Compare deux candidats analysés sur la même offre : scores, points forts, lacunes, et profil le plus adapté.';
    }

    public function handle(Request $request): Stringable|string
    {
        $analyses = Analyse::whereIn('candidat_id', [$request['candidat_id_1'], $request['candidat_id_2']])
            ->with('candidat')
            ->get();

        if ($analyses->count() < 2) {
            return 'Impossible de comparer : un ou les deux candidats n\'ont pas d\'analyse.';
        }

        $data = [];
        foreach ($analyses as $analyse) {
            $data[] = [
                'candidat_id' => $analyse->candidat_id,
                'candidat_nom' => $analyse->candidat->nom,
                'matching_score' => $analyse->matching_score,
                'points_forts' => $analyse->points_forts,
                'lacunes' => $analyse->lacunes,
                'recommandation' => $analyse->recommandation?->value,
            ];
        }

        $comparison = $data[0]['matching_score'] >= $data[1]['matching_score']
            ? $data[0]
            : $data[1];

        return json_encode([
            'candidats' => $data,
            'meilleur_profil' => $comparison['candidat_nom'],
            'ecart_score' => abs($data[0]['matching_score'] - $data[1]['matching_score']),
        ], JSON_UNESCAPED_UNICODE);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'candidat_id_1' => $schema->integer()
                ->description('ID du premier candidat')
                ->required(),
            'candidat_id_2' => $schema->integer()
                ->description('ID du second candidat')
                ->required(),
        ];
    }
}
