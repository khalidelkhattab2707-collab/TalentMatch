<?php

namespace App\Ai\Agents;

use App\Ai\Tools\CompareCandidatesTool;
use App\Ai\Tools\GetCandidateAnalysisTool;
use App\Ai\Tools\GetJobRequirementsTool;
use App\Models\Candidat;
use Laravel\Ai\Attributes\MaxSteps;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Groq)]
#[Model('llama-3.3-70b-versatile')]
#[MaxSteps(10)]
class RhAssistantAgent implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    public function __construct(
        public Candidat $candidat,
    ) {}

    public function instructions(): Stringable|string
    {
        $offre = $this->candidat->offre;
        $analyse = $this->candidat->analyse;

        $context = "Tu es un assistant RH expert. Tu aides les recruteurs à analyser les candidatures.\n\n"
            ."Candidat : {$this->candidat->nom}\n"
            ."Offre : {$offre->titre}\n";

        if ($analyse) {
            $context .= "Analyse disponible :\n"
                ."- Score : {$analyse->matching_score}/100\n"
                ."- Recommandation : {$analyse->recommandation?->value}\n";
        }

        $context .= "\nTu DOIS utiliser les outils disponibles pour répondre avec des données réelles. "
            .'Ne réponds jamais avec des données inventées. '
            .'Réponds en français de façon claire et professionnelle.';

        return $context;
    }

    public function tools(): iterable
    {
        return [
            new GetCandidateAnalysisTool,
            new GetJobRequirementsTool,
            new CompareCandidatesTool,
        ];
    }
}
