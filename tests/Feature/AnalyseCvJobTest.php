<?php

use App\AI\Schemas\AnalyseCvSchema;
use App\Enums\StatutJobEnum;
use App\Exceptions\AnalyseIAException;
use App\Jobs\AnalyseCvJob;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('dispatches AnalyseCvJob when candidat is created', function () {
    Queue::fake();

    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $this->post(route('offres.candidats.store', $offre), [
        'nom' => 'Jean Dupont',
        'cv_texte' => 'Développeur PHP avec 5 ans d\'expérience dans le développement web.',
    ]);

    Queue::assertPushed(AnalyseCvJob::class);
});

it('creates analyse record when job executes successfully', function () {
    AnalyseCvSchema::fake([
        json_encode([
            'competences_extraites' => ['PHP', 'Laravel', 'MySQL'],
            'annees_experience' => 5,
            'niveau_etudes' => 'Master',
            'langues' => ['Français', 'Anglais'],
            'matching_score' => 85,
            'points_forts' => ['Expert Laravel'],
            'lacunes' => ['Pas de React'],
            'competences_manquantes' => ['React'],
            'recommandation' => 'convoquer',
            'justification' => 'Excellent profil correspondant aux critères.',
        ]),
    ]);

    $offre = Offre::factory()->create([
        'user_id' => $this->user->id,
        'titre' => 'Développeur Laravel',
        'competences_requises' => ['PHP', 'Laravel', 'MySQL'],
        'experience_minimum' => 2,
    ]);
    $candidat = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'cv_texte' => 'Expert PHP avec 5 ans de Laravel et MySQL.',
    ]);

    (new AnalyseCvJob($candidat))->handle();

    expect($candidat->fresh()->statut_job)->toBe(StatutJobEnum::Analyse);
    expect(Analyse::where('candidat_id', $candidat->id)->exists())->toBeTrue();
});

it('marks candidat as echec when job fails', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $candidat = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::EnCours,
    ]);

    $job = new AnalyseCvJob($candidat);
    $job->failed(new Exception('Test error'));

    expect($candidat->fresh()->statut_job)->toBe(StatutJobEnum::Echec);
});

it('updates candidat status through the analysis lifecycle', function () {
    AnalyseCvSchema::fake([
        json_encode([
            'competences_extraites' => ['PHP', 'Laravel', 'MySQL'],
            'annees_experience' => 5,
            'niveau_etudes' => 'Master',
            'langues' => ['Français', 'Anglais'],
            'matching_score' => 85,
            'points_forts' => ['Expert Laravel'],
            'lacunes' => ['Pas de React'],
            'competences_manquantes' => ['React'],
            'recommandation' => 'convoquer',
            'justification' => 'Excellent profil correspondant aux critères.',
        ]),
    ]);

    $offre = Offre::factory()->create([
        'user_id' => $this->user->id,
        'titre' => 'Développeur Laravel',
        'competences_requises' => ['PHP', 'Laravel', 'MySQL'],
        'experience_minimum' => 2,
    ]);
    $candidat = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::EnAttente,
        'cv_texte' => 'Expert PHP avec 5 ans de Laravel et MySQL.',
    ]);

    expect($candidat->statut_job)->toBe(StatutJobEnum::EnAttente);

    (new AnalyseCvJob($candidat))->handle();

    expect($candidat->fresh()->statut_job)->toBe(StatutJobEnum::Analyse);
});

it('validates a correct response schema', function () {
    $data = [
        'competences_extraites' => ['PHP'],
        'annees_experience' => 3,
        'niveau_etudes' => 'Licence',
        'langues' => ['Français'],
        'matching_score' => 75,
        'points_forts' => ['Bon développeur'],
        'lacunes' => ['Manque d\'expérience'],
        'competences_manquantes' => ['Docker'],
        'recommandation' => 'attente',
        'justification' => 'Profil correct.',
    ];

    $result = AnalyseCvSchema::validateResponse($data);

    expect($result)->toBe($data);
});

it('rejects a response with missing fields', function () {
    $data = [
        'competences_extraites' => ['PHP'],
        'annees_experience' => 3,
    ];

    AnalyseCvSchema::validateResponse($data);
})->throws(AnalyseIAException::class, 'Champ manquant');

it('rejects a response with invalid matching_score', function () {
    $data = [
        'competences_extraites' => ['PHP'],
        'annees_experience' => 3,
        'niveau_etudes' => 'Licence',
        'langues' => ['Français'],
        'matching_score' => 150,
        'points_forts' => ['Bon développeur'],
        'lacunes' => ['Manque d\'expérience'],
        'competences_manquantes' => ['Docker'],
        'recommandation' => 'attente',
        'justification' => 'Profil correct.',
    ];

    AnalyseCvSchema::validateResponse($data);
})->throws(AnalyseIAException::class, 'matching_score');

it('rejects a response with invalid recommandation', function () {
    $data = [
        'competences_extraites' => ['PHP'],
        'annees_experience' => 3,
        'niveau_etudes' => 'Licence',
        'langues' => ['Français'],
        'matching_score' => 75,
        'points_forts' => ['Bon développeur'],
        'lacunes' => ['Manque d\'expérience'],
        'competences_manquantes' => ['Docker'],
        'recommandation' => 'invalide',
        'justification' => 'Profil correct.',
    ];

    AnalyseCvSchema::validateResponse($data);
})->throws(AnalyseIAException::class, 'recommandation');

it('retries analysis via the retry route', function () {
    Queue::fake();

    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $candidat = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::Echec,
    ]);

    $this->post(route('offres.candidats.retry-analyse', [$offre, $candidat]));

    expect($candidat->fresh()->statut_job)->toBe(StatutJobEnum::EnAttente);
    Queue::assertPushed(AnalyseCvJob::class);
});
