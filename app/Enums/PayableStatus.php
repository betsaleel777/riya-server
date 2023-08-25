<?php

namespace App\Enums;

enum PayableStatus: string
{
    case PAID = 'payé';
    case UNPAID = 'impayé';
    case PENDING = 'en attente';
}
