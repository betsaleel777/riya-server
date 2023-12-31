<?php

namespace App\Repositories;

use App\Interfaces\DetteRepositoryInterface;
use App\Models\Contrat;
use App\Models\Dette;
use App\Models\Paiement;
use App\Models\Visite;

class DetteRepository implements DetteRepositoryInterface
{
    public function storeForRental(Contrat $contrat, Visite $visite): void
    {
        $visite->load('caution', 'avance', 'appartement');
        $montant = $visite->caution->mois * $visite->appartement->montant_location + $visite->avance->mois * $visite->appartement->montant_location * $contrat->commission / 100;
        $dette = Dette::make(['montant' => $montant]);
        $dette->genererCode();
        $dette->origine()->associate($visite)->save();
    }

    public function storeForPayement(Paiement $paiement, Contrat $contrat): void
    {
        $dette = Dette::make(['montant' => $paiement->montant * $contrat->commission / 100]);
        $dette->genererCode();
        $dette->origine()->associate($paiement)->save();
    }
}
