<?php

namespace App\Repositories;

use App\Interfaces\AchatRepositoryInterface;
use App\Models\Achat;
use App\Models\Appartement;
use App\Models\Terrain;

class AchatRepository implements AchatRepositoryInterface
{
    public function firstCheckUptodate(Appartement|Terrain $bien, int $montant): bool
    {
        return $montant >= $bien->cout_achat;
    }

    public function checkUptodate(Achat $achat): bool
    {
        $bien = $achat->load('bien')->bien;
        return $achat->paiements->isEmpty() ?: $achat->paiements->sum('montant') >= $bien->cout_achat;
    }

    public function cascadeAchatUptodate(Achat $achat): void
    {
        $achat->load('contrat');
        $achat->uptodate = $this->checkUptodate($achat);
        !$achat->uptodate ?: $achat->contrat->setUptodate();
        $achat->save();
    }
}
