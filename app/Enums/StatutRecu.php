<?php

namespace App\Enums;

enum StatutRecu: string
{
    case EnAttente = 'en_attente';
    case Traite = 'traite';
    case Echoue = 'echoue';
}
