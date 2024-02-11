<?php

namespace App\Tasks;

use App\Models\Contrat;
use App\Models\Loyer;
use App\Repositories\DetteRepository;
use App\Repositories\LoyerRepository;

class CreateRent
{
    public function __invoke()
    {
        //recupérer la liste des contrats en cours
        $contrats = Contrat::with('operation.appartement')->rentProcessing()->get();
        //créer un loyer pour chaque contrat si la date butoire est arrivée
        $loyers = Loyer::currentMonth()->get();
        $contrats->each(function (Contrat $contrat) use ($loyers) {
            if ($contrat->encaissable() and !$loyers->contains('contrat_id', $contrat->id)) {
                $loyerRepository = new LoyerRepository();
                $loyer = $loyerRepository->create($contrat);
                $contrat->setNotuptodate();
                $detteRepository = new DetteRepository();
                $detteRepository->storeForRent($loyer, $contrat);
            }
        });
    }
}
