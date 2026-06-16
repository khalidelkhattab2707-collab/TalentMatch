<?php

use App\Enums\StatutJobEnum;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('shows comparison page with two analysed candidates', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $candidat1 = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::Analyse,
    ]);
    $analyse1 = Analyse::factory()->create([
        'candidat_id' => $candidat1->id,
        'matching_score' => 75,
    ]);
    $candidat2 = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::Analyse,
    ]);
    $analyse2 = Analyse::factory()->create([
        'candidat_id' => $candidat2->id,
        'matching_score' => 85,
    ]);

    $response = $this->get(route('offres.comparer', [$offre, 'candidats' => [$candidat1->id, $candidat2->id]]));

    $response->assertOk();
    $response->assertSee($candidat1->nom);
    $response->assertSee($candidat2->nom);
    $response->assertSee('Meilleur profil');
    $response->assertSee('10%');
});

it('fails with less than two candidate ids', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = $this->get(route('offres.comparer', [$offre, 'candidats' => [1]]));

    $response->assertStatus(422);
});

it('fails if candidate lacks analysis', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $candidat1 = Candidat::factory()->create(['offre_id' => $offre->id]);
    $candidat2 = Candidat::factory()->create(['offre_id' => $offre->id]);

    Analyse::factory()->create(['candidat_id' => $candidat1->id]);

    $response = $this->get(route('offres.comparer', [$offre, 'candidats' => [$candidat1->id, $candidat2->id]]));

    $response->assertStatus(422);
});

it('fails for candidates from another user offre', function () {
    $otherUser = User::factory()->create();
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $otherOffre = Offre::factory()->create(['user_id' => $otherUser->id]);
    $candidat1 = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::Analyse,
    ]);
    $candidat2 = Candidat::factory()->create([
        'offre_id' => $otherOffre->id,
        'statut_job' => StatutJobEnum::Analyse,
    ]);
    Analyse::factory()->create(['candidat_id' => $candidat1->id]);
    Analyse::factory()->create(['candidat_id' => $candidat2->id]);

    $response = $this->get(route('offres.comparer', [$offre, 'candidats' => [$candidat1->id, $candidat2->id]]));

    $response->assertForbidden();
});

it('redirects unauthenticated users to login', function () {
    auth()->logout();
    $offre = Offre::factory()->create();

    $response = $this->get(route('offres.comparer', [$offre, 'candidats' => [1, 2]]));

    $response->assertRedirect(route('login'));
});

it('prevents accessing comparison for another user offre', function () {
    $otherUser = User::factory()->create();
    $offre = Offre::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->get(route('offres.comparer', [$offre, 'candidats' => [1, 2]]));

    $response->assertForbidden();
});
