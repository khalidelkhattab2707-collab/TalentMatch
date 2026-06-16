<?php

namespace App\Ai\Tools;

use App\Models\Offre;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetJobRequirementsTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Récupère les critères et compétences requises d\'une offre d\'emploi : titre, description, compétences requises, expérience minimum.';
    }

    public function handle(Request $request): Stringable|string
    {
        $offre = Offre::find($request['offre_id']);

        if (! $offre) {
            return "Aucune offre trouvée avec l'ID {$request['offre_id']}.";
        }

        return json_encode([
            'titre' => $offre->titre,
            'description' => $offre->description,
            'competences_requises' => $offre->competences_requises,
            'experience_minimum' => $offre->experience_minimum,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'offre_id' => $schema->integer()
                ->description("ID de l'offre d'emploi")
                ->required(),
        ];
    }
}
