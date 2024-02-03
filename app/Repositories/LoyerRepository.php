<?php

namespace App\Repositories;

use App\Interfaces\LoyerRepositoryInterface;
use App\Models\Loyer;
use App\Models\Paiement;

class LoyerRepository implements LoyerRepositoryInterface
{

    public function checkUptodate(Loyer $loyer): bool
    {
        $bien = $loyer->loadMissing('bien')->bien;
        return $loyer->paiements->isEmpty() ?: $loyer->paiements->sum('montant') >= $bien->montant_location;
    }

    public function cascadeLoyerUptodate(Loyer $loyer): void
    {
        $loyer->loadMissing('contrat');
        if ($this->checkUptodate($loyer)) {
            $loyer->setPaid();
            $loyer->contrat->setUptodate();
        } else {
            $loyer->setUnpaid();
            $loyer->contrat->setNotuptodate();
        }
    }

    public function valider(Loyer $loyer): Paiement
    {
        $paiement = $loyer->loadMissing('pendingPaiement')->pendingPaiement;
        $paiement->setValide();
        $this->cascadeLoyerUptodate($loyer);
        return $paiement;
    }
}
