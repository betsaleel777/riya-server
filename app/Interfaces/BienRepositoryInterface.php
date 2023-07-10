<?php

namespace App\Interfaces;

use App\Models\Appartement;
use App\Models\Terrain;

interface BienRepositoryInterface
{
    public function getByType(int $bienId, string $type): Appartement|Terrain;
}
