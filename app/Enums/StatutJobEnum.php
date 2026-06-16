<?php

namespace App\Enums;

enum StatutJobEnum: string
{
    case EnAttente = 'en_attente';
    case EnCours = 'en_cours';
    case Analyse = 'analyse';
    case Echec = 'echec';
}
