<?php

namespace App\Repositories;

use App\Interfaces\AchatRepositoryInterface;
use App\Models\Achat;
use App\Models\Appartement;
use App\Models\Paiement;
use App\Models\Terrain;

class AchatRepository implements AchatRepositoryInterface
{
    public function firstCheckUptodate(Appartement|Terrain $bien, int $montant): bool
    {
        return $montant >= $bien->cout_achat;
    }

    public function checkUptodate(Appartement|Terrain $bien, Achat $achat): bool
    {
        return $achat->paiements->isEmpty() ?: $achat->paiements->sum('montant') >= $bien->cout_achat;
    }

    public function createPaiement(int $achatId, int $montant): Paiement
    {
        $paiement = Paiement::make(['achat_id' => $achatId, 'montant' => $montant]);
        $paiement->genererCode();
        $paiement->save();
        return $paiement;
    }
}
