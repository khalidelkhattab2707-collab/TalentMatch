<?php

namespace App\Http\Controllers;

use App\Ai\Agents\RhAssistantAgent;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Candidat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Ai\Models\Conversation;

class ConversationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $candidat = Candidat::findOrFail($request->input('candidat_id'));

        Gate::authorize('view', $candidat->offre);

        $user = auth()->user();

        $existing = Conversation::where('user_id', $user->id)
            ->where('title', "Candidat : {$candidat->nom}")
            ->first();

        if ($existing && $existing->messages()->exists()) {
            return to_route('conversations.show', $existing);
        }

        $conversation = Conversation::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'title' => "Candidat : {$candidat->nom}",
        ]);

        (new RhAssistantAgent($candidat))
            ->continue($conversation->id, as: $user)
            ->prompt('Bonjour, présente-moi le candidat.');

        return to_route('conversations.show', $conversation);
    }

    public function show(Conversation $conversation): View
    {
        $conversation->loadMissing('messages');

        return view('conversations.show', compact('conversation'));
    }

    public function sendMessage(StoreMessageRequest $request, Conversation $conversation): RedirectResponse
    {
        $user = auth()->user();

        $title = $conversation->title;
        $candidatNom = str_starts_with($title, 'Candidat : ')
            ? substr($title, strlen('Candidat : '))
            : null;

        $candidat = $candidatNom
            ? Candidat::where('nom', $candidatNom)->first()
            : null;

        if (! $candidat) {
            return to_route('conversations.show', $conversation)
                ->with('error', __('Impossible de trouver le candidat associé.'));
        }

        (new RhAssistantAgent($candidat))
            ->continue($conversation->id, $user)
            ->prompt($request->input('contenu'));

        return to_route('conversations.show', $conversation);
    }
}
