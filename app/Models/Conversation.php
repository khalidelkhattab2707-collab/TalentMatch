<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Ai\Models\Conversation as BaseConversation;

class Conversation extends BaseConversation
{
    public function getCandidatAttribute(): ?Candidat
    {
        $title = $this->title;

        $nom = str_starts_with($title, 'Candidat : ')
            ? substr($title, strlen('Candidat : '))
            : null;

        return $nom
            ? Candidat::where('nom', $nom)->first()
            : null;
    }

    /** @param Builder<self> $query */
    public function scopeByUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }
}
