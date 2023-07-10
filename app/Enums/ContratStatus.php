<?php

namespace App\Enums;

enum ContratStatus: string
{
    case UPTODATE = 'à jours';
    case NOTUPTODATE = 'non à jours';
}
