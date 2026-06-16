<?php

use App\Ai\Agents\RhAssistantAgent;
use App\Enums\StatutJobEnum;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Support\Str;

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

it('returns candidat from conversation model', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $candidat = Candidat::factory()->create([
        'offre_id' => $offre->id,
        'statut_job' => StatutJobEnum::Analyse,
    ]);
    Analyse::factory()->create(['candidat_id' => $candidat->id]);

    $this->post(route('conversations.store'), [
        'candidat_id' => $candidat->id,
    ]);

    $conversation = Conversation::first();

    expect($conversation)->toBeInstanceOf(Laravel\Ai\Models\Conversation::class);
    expect($conversation->candidat)->not->toBeNull();
    expect($conversation->candidat->id)->toBe($candidat->id);
});

it('returns excerpt from message model', function () {
    $conversation = Conversation::create([
        'id' => (string) Str::uuid(),
        'user_id' => $this->user->id,
        'title' => 'Test',
    ]);

    $message = Message::create([
        'id' => (string) Str::uuid(),
        'conversation_id' => $conversation->id,
        'agent' => 'RhAssistantAgent',
        'role' => 'assistant',
        'content' => 'Une longue réponse avec beaucoup de détails qui devrait être tronquée après cent caractères.',
        'attachments' => [],
        'tool_calls' => [],
        'tool_results' => [],
        'usage' => [],
        'meta' => [],
    ]);

    $excerpt = $message->excerpt(50);

    expect(mb_strlen($excerpt))->toBeLessThanOrEqual(51);
    expect($excerpt)->toEndWith('…');
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
