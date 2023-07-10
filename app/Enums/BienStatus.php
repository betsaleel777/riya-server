<?php

namespace App\Enums;

enum BienStatus: string
{
    case BUSY = 'occupé';
    case FREE = 'disponible';
}
