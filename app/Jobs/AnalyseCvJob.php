<?php

namespace App\Jobs;

use App\AI\Schemas\AnalyseCvSchema;
use App\Enums\StatutJobEnum;
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

        $response = (new AnalyseCvSchema)->prompt($prompt);

        Analyse::create([
            'candidat_id' => $this->candidat->id,
            'competences_extraites' => $response['competences_extraites'],
            'annees_experience' => $response['annees_experience'],
            'niveau_etudes' => $response['niveau_etudes'],
            'langues' => $response['langues'],
            'matching_score' => $response['matching_score'],
            'points_forts' => $response['points_forts'],
            'lacunes' => $response['lacunes'],
            'competences_manquantes' => $response['competences_manquantes'],
            'recommandation' => $response['recommandation'],
            'justification' => $response['justification'],
        ]);

        $this->candidat->update(['statut_job' => StatutJobEnum::Analyse]);
    }

    public function failed(): void
    {
        $this->candidat->update(['statut_job' => StatutJobEnum::Echec]);

        Log::error('AnalyseCvJob failed', [
            'candidat_id' => $this->candidat->id,
            'candidat_nom' => $this->candidat->nom,
        ]);
    }
}
