<?php

namespace App\Models;

use App\Enums\StatutOffreEnum;
use Database\Factories\OffreFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offre extends Model
{
    /** @use HasFactory<OffreFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'competences_requises',
        'experience_minimum',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'competences_requises' => 'array',
            'statut' => StatutOffreEnum::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function candidats(): HasMany
    {
        return $this->hasMany(Candidat::class);
    }

    public function scopeActive($query)
    {
        return $query->where('statut', StatutOffreEnum::Active);
    }
}
