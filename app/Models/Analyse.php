<?php

namespace App\Models;

use App\Enums\RecommandationEnum;
use Database\Factories\AnalyseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Analyse extends Model
{
    /** @use HasFactory<AnalyseFactory> */
    use HasFactory;

    protected $fillable = [
        'candidat_id', 'competences_extraites', 'annees_experience',
        'niveau_etudes', 'langues', 'matching_score', 'points_forts',
        'lacunes', 'competences_manquantes', 'recommandation', 'justification',
    ];

    protected function casts(): array
    {
        return [
            'competences_extraites' => 'array',
            'annees_experience' => 'integer',
            'langues' => 'array',
            'matching_score' => 'integer',
            'points_forts' => 'array',
            'lacunes' => 'array',
            'competences_manquantes' => 'array',
            'recommandation' => RecommandationEnum::class,
        ];
    }

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidat::class);
    }
}
