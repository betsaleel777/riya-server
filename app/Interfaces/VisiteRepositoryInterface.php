<?php

namespace App\Interfaces;

use App\Models\Visite;

interface VisiteRepositoryInterface
{
    public function emitBailProcess(int | Visite $rental): Visite;
}
