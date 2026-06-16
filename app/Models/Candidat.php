<?php

namespace App\Models;

use App\Enums\StatutJobEnum;
use Database\Factories\CandidatFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Candidat extends Model
{
    /** @use HasFactory<CandidatFactory> */
    use HasFactory;

    protected $fillable = [
        'offre_id', 'nom', 'cv_texte', 'statut_job',
    ];

    protected function casts(): array
    {
        return [
            'statut_job' => StatutJobEnum::class,
        ];
    }

    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class);
    }

    public function analyse(): HasOne
    {
        return $this->hasOne(Analyse::class);
    }
}
