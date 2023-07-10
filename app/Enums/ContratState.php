<?php

namespace App\Enums;

enum ContratState: string
{
    case USING = 'en cours';
    case ABORTED = 'résilié';
}
