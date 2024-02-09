<?php

namespace App\Tasks;

use App\Models\Contrat;
use App\Models\Loyer;
use App\Repositories\DetteRepository;

class CreateRent
{
    public function __invoke()
    {
        //recupérer la liste des contrats pour appartements en cours
        $contrats = Contrat::with('operation.appartement')->rentProcessing()->get();
        //créer un loyer pour chaque contrat si la date butoire est arrivée
        $loyers = Loyer::currentMonth()->get();
        $contrats->each(function (Contrat $contrat) use ($loyers) {
            if ($contrat->encaissable() and !$loyers->contains('contrat_id', $contrat->id)) {
                /** @var Appartement $appartement */
                $appartement = $contrat->operation->appartement;
                /** @var Loyer $loyer */
                $loyer = Loyer::make(['montant' => $appartement->montant_location, 'contrat_id' => $contrat->id]);
                $loyer->genererCode();
                $loyer->save();
                $contrat->setNotuptodate();
                $detteRepository = new DetteRepository();
                $detteRepository->storeForRent($loyer, $contrat);
            }
        });
    }
}
