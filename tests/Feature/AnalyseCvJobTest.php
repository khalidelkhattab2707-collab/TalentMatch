<?php

use App\AI\Schemas\AnalyseCvSchema;
use App\Enums\StatutJobEnum;
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
    AnalyseCvSchema::fake();

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
    $job->failed();

    expect($candidat->fresh()->statut_job)->toBe(StatutJobEnum::Echec);
});

it('updates candidat status through the analysis lifecycle', function () {
    AnalyseCvSchema::fake();

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
