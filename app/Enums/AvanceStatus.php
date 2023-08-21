<?php

namespace App\Enums;

enum AvanceStatus: string
{
    case EXHAUSTED = 'avance épuisée';
    case INUSE = 'avance en cours';
    case CONTRACTWITHOUT = 'simple visite';
}
