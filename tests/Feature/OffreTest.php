<?php

use App\Models\Offre;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('lists the user offres', function () {
    Offre::factory()->count(3)->create(['user_id' => $this->user->id]);

    $response = $this->get(route('offres.index'));

    $response->assertOk();
});

it('shows the create form', function () {
    $response = $this->get(route('offres.create'));

    $response->assertOk();
});

it('creates a new offre', function () {
    $response = $this->post(route('offres.store'), [
        'titre' => 'Développeur Laravel',
        'description' => 'Nous recherchons un développeur Laravel expérimenté pour rejoindre notre équipe.',
        'competences_requises' => ['PHP', 'Laravel', 'MySQL'],
        'experience_minimum' => 3,
    ]);

    $this->assertDatabaseHas('offres', [
        'titre' => 'Développeur Laravel',
        'user_id' => $this->user->id,
    ]);

    $offre = Offre::where('titre', 'Développeur Laravel')->first();
    $response->assertRedirect(route('offres.show', $offre));
});

it('validates the store request', function () {
    $response = $this->post(route('offres.store'), []);

    $response->assertSessionHasErrors(['titre', 'description', 'competences_requises', 'experience_minimum']);
});

it('validates competences_requises minimum one item', function () {
    $response = $this->post(route('offres.store'), [
        'titre' => 'Test',
        'description' => 'Description suffisamment longue pour passer la validation.',
        'competences_requises' => [],
        'experience_minimum' => 0,
    ]);

    $response->assertSessionHasErrors(['competences_requises']);
});

it('shows an offre', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = $this->get(route('offres.show', $offre));

    $response->assertOk();
    $response->assertSee($offre->titre);
});

it('prevents viewing another user offre', function () {
    $otherUser = User::factory()->create();
    $offre = Offre::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->get(route('offres.show', $offre));

    $response->assertForbidden();
});

it('shows the edit form', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = $this->get(route('offres.edit', $offre));

    $response->assertOk();
});

it('prevents editing another user offre', function () {
    $otherUser = User::factory()->create();
    $offre = Offre::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->get(route('offres.edit', $offre));

    $response->assertForbidden();
});

it('updates an offre', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = $this->put(route('offres.update', $offre), [
        'titre' => 'Titre modifié',
        'description' => 'Description mise à jour avec suffisamment de caractères.',
        'competences_requises' => ['PHP'],
        'experience_minimum' => 5,
    ]);

    $response->assertRedirect(route('offres.show', $offre));
    expect($offre->fresh()->titre)->toBe('Titre modifié');
});

it('prevents updating another user offre', function () {
    $otherUser = User::factory()->create();
    $offre = Offre::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->put(route('offres.update', $offre), [
        'titre' => 'Titre modifié',
        'description' => 'Description mise à jour avec suffisamment de caractères.',
        'competences_requises' => ['PHP'],
        'experience_minimum' => 5,
    ]);

    $response->assertForbidden();
});

it('deletes an offre', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = $this->delete(route('offres.destroy', $offre));

    $response->assertRedirect(route('offres.index'));
    $this->assertModelMissing($offre);
});

it('prevents deleting another user offre', function () {
    $otherUser = User::factory()->create();
    $offre = Offre::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->delete(route('offres.destroy', $offre));

    $response->assertForbidden();
});

it('redirects unauthenticated users to login', function () {
    auth()->logout();

    $response = $this->get(route('offres.index'));

    $response->assertRedirect(route('login'));
});
