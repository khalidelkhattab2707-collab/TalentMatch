<?php

use App\Ai\Agents\RhAssistantAgent;
use App\Enums\StatutJobEnum;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('starts a conversation for a candidat', function () {
    RhAssistantAgent::fake();

    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $candidat = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::Analyse,
    ]);
    Analyse::factory()->create(['candidat_id' => $candidat->id]);

    $this->post(route('conversations.store'), [
        'candidat_id' => $candidat->id,
    ])->assertRedirect();

    $this->assertDatabaseHas('agent_conversations', [
        'title' => "Candidat : {$candidat->nom}",
    ]);
});

it('sends a message in a conversation', function () {
    RhAssistantAgent::fake();

    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $candidat = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::Analyse,
    ]);
    Analyse::factory()->create(['candidat_id' => $candidat->id]);

    $response = $this->post(route('conversations.store'), [
        'candidat_id' => $candidat->id,
    ]);

    $conversationId = basename($response->headers->get('Location'));

    $this->post(route('conversations.message', $conversationId), [
        'contenu' => 'Quel est le score de ce candidat ?',
    ])->assertRedirect();
});

it('shows the conversation page', function () {
    RhAssistantAgent::fake();

    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $candidat = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::Analyse,
    ]);
    Analyse::factory()->create(['candidat_id' => $candidat->id]);

    $response = $this->post(route('conversations.store'), [
        'candidat_id' => $candidat->id,
    ]);

    $conversationId = basename($response->headers->get('Location'));

    $this->get(route('conversations.show', $conversationId))
        ->assertOk()
        ->assertSee('Candidat');
});

it('validates message content', function () {
    RhAssistantAgent::fake();

    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $candidat = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::Analyse,
    ]);
    Analyse::factory()->create(['candidat_id' => $candidat->id]);

    $response = $this->post(route('conversations.store'), [
        'candidat_id' => $candidat->id,
    ]);

    $conversationId = basename($response->headers->get('Location'));

    $this->post(route('conversations.message', $conversationId), [
        'contenu' => '',
    ])->assertSessionHasErrors('contenu');
});
