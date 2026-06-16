<?php

namespace App\Jobs;

use App\AI\Schemas\AnalyseCvSchema;
use App\Enums\StatutJobEnum;
use App\Exceptions\AnalyseIAException;
use App\Models\Analyse;
use App\Models\Candidat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AnalyseCvJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct(
        public Candidat $candidat
    ) {}

    public function handle(): void
    {
        $this->candidat->update(['statut_job' => StatutJobEnum::EnCours]);

        $offre = $this->candidat->offre;

        $prompt = "Analyse ce CV en le comparant à l'offre d'emploi.\n\n"
            ."Offre : {$offre->titre}\n"
            .'Compétences requises : '.implode(', ', $offre->competences_requises ?? [])."\n"
            ."Expérience minimum : {$offre->experience_minimum} ans.\n\n"
            ."CV du candidat :\n{$this->candidat->cv_texte}";

        $responseText = (new AnalyseCvSchema)->prompt($prompt);

        $decoded = json_decode((string) $responseText, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new AnalyseIAException(
                'Réponse IA invalide : impossible de décoder le JSON ('.json_last_error_msg().')'
            );
        }

        $validated = AnalyseCvSchema::validateResponse($decoded);

        Analyse::create([
            'candidat_id' => $this->candidat->id,
            'competences_extraites' => $validated['competences_extraites'],
            'annees_experience' => $validated['annees_experience'],
            'niveau_etudes' => $validated['niveau_etudes'],
            'langues' => $validated['langues'],
            'matching_score' => $validated['matching_score'],
            'points_forts' => $validated['points_forts'],
            'lacunes' => $validated['lacunes'],
            'competences_manquantes' => $validated['competences_manquantes'],
            'recommandation' => $validated['recommandation'],
            'justification' => $validated['justification'],
        ]);

        $this->candidat->update(['statut_job' => StatutJobEnum::Analyse]);
    }

    public function failed(\Throwable $e): void
    {
        $this->candidat->update(['statut_job' => StatutJobEnum::Echec]);

        Log::error('AnalyseCvJob failed', [
            'candidat_id' => $this->candidat->id,
            'candidat_nom' => $this->candidat->nom,
            'error' => $e->getMessage(),
        ]);
    }
}
