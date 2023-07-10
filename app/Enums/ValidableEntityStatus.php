<?php

namespace App\Enums;

enum ValidableEntityStatus: string
{
    case WAIT = 'en attente';
    case VALID = 'validée';
}
