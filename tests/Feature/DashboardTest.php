<?php

use App\Enums\RecommandationEnum;
use App\Enums\StatutJobEnum;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('shows dashboard with stats for authenticated user', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $candidat = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::Analyse,
    ]);
    Analyse::factory()->create([
        'candidat_id' => $candidat->id,
        'matching_score' => 75,
        'recommandation' => RecommandationEnum::Convoquer,
    ]);

    $response = $this->actingAs($this->user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('1');
    $response->assertSee('75');
});

it('shows dashboard empty state', function () {
    $response = $this->actingAs($this->user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee(__('Aucun candidat pour le moment.'));
});

it('redirects unauthenticated users to login', function () {
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});

it('filters candidates by recommendation on dashboard', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $candidat1 = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::Analyse,
    ]);
    Analyse::factory()->create([
        'candidat_id' => $candidat1->id,
        'recommandation' => RecommandationEnum::Convoquer,
    ]);
    $candidat2 = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::Analyse,
    ]);
    Analyse::factory()->create([
        'candidat_id' => $candidat2->id,
        'recommandation' => RecommandationEnum::Rejeter,
    ]);

    $response = $this->actingAs($this->user)->get(route('dashboard', ['recommandation' => 'convoquer']));

    $response->assertOk();
});

it('searches candidates by name on dashboard', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    Candidat::factory()->create([
        'offre_id' => $offre->id,
        'nom' => 'Jean Dupont',
    ]);
    Candidat::factory()->create([
        'offre_id' => $offre->id,
        'nom' => 'Marie Martin',
    ]);

    $response = $this->actingAs($this->user)->get(route('dashboard', ['search' => 'Jean']));

    $response->assertOk();
});

it('shows offre detail page with sorted candidates', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $candidat1 = Candidat::factory()->create(['offre_id' => $offre->id]);
    Analyse::factory()->create([
        'candidat_id' => $candidat1->id,
        'matching_score' => 50,
    ]);
    $candidat2 = Candidat::factory()->create(['offre_id' => $offre->id]);
    Analyse::factory()->create([
        'candidat_id' => $candidat2->id,
        'matching_score' => 90,
    ]);

    $response = $this->actingAs($this->user)->get(route('offres.show', $offre));

    $response->assertOk();
    $response->assertSee($candidat1->nom);
    $response->assertSee($candidat2->nom);
});
