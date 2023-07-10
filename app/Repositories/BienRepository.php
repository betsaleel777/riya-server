<?php

namespace App\Repositories;

use App\Enums\BienType;
use App\Interfaces\BienRepositoryInterface;
use App\Models\Appartement;
use App\Models\Terrain;

class BienRepository implements BienRepositoryInterface
{
    public function getByType(int $bienId, string $type): Appartement|Terrain
    {
        return $type === BienType::APPARTEMENT->value ? Appartement::find($bienId) : Terrain::find($bienId);
    }
}
