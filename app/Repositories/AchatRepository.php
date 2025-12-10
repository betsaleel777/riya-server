<?php

namespace App\Repositories;

use App\Events\PaiementValidated;
use App\Interfaces\AchatRepositoryInterface;
use App\Models\Achat;
use App\Models\Appartement;
use App\Models\Paiement;
use App\Models\Terrain;

class AchatRepository implements AchatRepositoryInterface
{
    public function firstCheckUptodate(Appartement | Terrain $bien, int $montant): bool
    {
        return $montant >= $bien->cout_achat;
    }

    public function checkUptodate(Achat $achat): bool
    {
        $achat->loadMissing('contrat');
        $coutAchat = $achat->contrat?->cout_achat;
        return $achat->paiements->isEmpty() ?: $achat->paiements->sum('montant') >= $coutAchat;
    }

    public function cascadeAchatUptodate(Achat $achat): void
    {
        $achat->loadMissing('contrat');
        $achat->uptodate = $this->checkUptodate($achat);
        !$achat->uptodate ?: $achat->contrat->setUptodate();
        $achat->save();
    }

    public function valider(Achat $achat): Paiement
    {
        $paiement = $achat->pendingPaiement()->first();
        $paiement->setValide();
        PaiementValidated::dispatch($paiement);
        return $paiement;
    }
}
