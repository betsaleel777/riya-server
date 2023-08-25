<?php

namespace App\Interfaces;

use App\Models\Achat;
use App\Models\Appartement;
use App\Models\Terrain;

interface AchatRepositoryInterface
{
    public function firstCheckUptodate(Appartement|Terrain $bien, int $montant): bool;
    public function checkUptodate(Achat $achat): bool;
    public function cascadeAchatUptodate(Achat $achat): void;
}
