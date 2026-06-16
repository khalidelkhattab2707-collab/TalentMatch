<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analyse extends Model
{
    protected $fillable = [
        'candidat_id', 'competences_extraites', 'annees_experience',
        'niveau_etudes', 'langues', 'matching_score', 'points_forts',
        'lacunes', 'competences_manquantes', 'recommandation', 'justification',
    ];
}
