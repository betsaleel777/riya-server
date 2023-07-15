<?php

namespace App\Enums;

enum PaiementType: string
{
    case ACHAT = 'Achat';
    case LOYER = 'Loyer';
    case DETTE = 'Dette';
}
