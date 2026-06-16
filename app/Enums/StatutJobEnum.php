<?php

namespace App\Enums;

enum StatutJobEnum: string
{
    case EnAttente = 'en_attente';
    case Accepte = 'accepte';
    case Refuse = 'refuse';
}
