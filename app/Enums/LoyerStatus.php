<?php

namespace App\Enums;

enum LoyerStatus: string
{
    case PAID = 'payé';
    case UNPAID = 'impayé';
    case PENDING = 'en attente';
}
