<?php

use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('shows the create form', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = $this->get(route('offres.candidats.create', $offre));

    $response->assertOk();
});

it('prevents showing the create form for another user offre', function () {
    $otherUser = User::factory()->create();
    $offre = Offre::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->get(route('offres.candidats.create', $offre));

    $response->assertForbidden();
});

it('creates a new candidat', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = $this->post(route('offres.candidats.store', $offre), [
        'nom' => 'Jean Dupont',
        'cv_texte' => 'Développeur PHP avec 5 ans d\'expérience.',
    ]);

    $this->assertDatabaseHas('candidats', [
        'nom' => 'Jean Dupont',
        'offre_id' => $offre->id,
    ]);

    $candidat = Candidat::where('nom', 'Jean Dupont')->first();
    $response->assertRedirect(route('offres.candidats.show', [$offre, $candidat]));
});

it('validates the store request', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = $this->post(route('offres.candidats.store', $offre), []);

    $response->assertSessionHasErrors(['nom', 'cv_texte']);
});

it('prevents creating a candidat for another user offre', function () {
    $otherUser = User::factory()->create();
    $offre = Offre::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->post(route('offres.candidats.store', $offre), [
        'nom' => 'Jean Dupont',
        'cv_texte' => 'Développeur PHP.',
    ]);

    $response->assertForbidden();
});

it('shows a candidat', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $candidat = Candidat::factory()->create(['offre_id' => $offre->id]);

    $response = $this->get(route('offres.candidats.show', [$offre, $candidat]));

    $response->assertOk();
    $response->assertSee($candidat->nom);
});

it('prevents viewing a candidat of another user offre', function () {
    $otherUser = User::factory()->create();
    $offre = Offre::factory()->create(['user_id' => $otherUser->id]);
    $candidat = Candidat::factory()->create(['offre_id' => $offre->id]);

    $response = $this->get(route('offres.candidats.show', [$offre, $candidat]));

    $response->assertForbidden();
});

it('redirects unauthenticated users to login', function () {
    $offre = Offre::factory()->create();
    $candidat = Candidat::factory()->create(['offre_id' => $offre->id]);

    auth()->logout();

    $this->get(route('offres.candidats.create', $offre))->assertRedirect(route('login'));
    $this->post(route('offres.candidats.store', $offre))->assertRedirect(route('login'));
    $this->get(route('offres.candidats.show', [$offre, $candidat]))->assertRedirect(route('login'));
});
