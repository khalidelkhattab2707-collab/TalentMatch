<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Candidat extends Model
{
    protected $fillable = [
        'offre_id', 'nom', 'cv_texte', 'statut_job',
    ];

    public function analyse(): HasOne
    {
        return $this->hasOne(Analyse::class);
    }
}
