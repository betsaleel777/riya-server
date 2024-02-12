<?php

namespace App\Repositories;

use App\Interfaces\LoyerRepositoryInterface;
use App\Interfaces\PaiementRepositoryInterface;
use App\Models\Contrat;
use App\Models\Loyer;
use App\Models\Paiement;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LoyerRepository implements LoyerRepositoryInterface
{

    private function prepareCreate(Contrat $contrat): Loyer
    {
        $contrat->loadMissing('operation.appartement');
        $loyer = Loyer::make([
            'montant' => $contrat->operation->appartement->montant_location,
            'contrat_id' => $contrat->id,
        ]);
        $loyer->genererCode();
        return $loyer;
    }

    public function create(Contrat $contrat): Loyer
    {
        $loyer = $this->prepareCreate($contrat);
        $loyer->mois = now()->format('Y-m');
        $loyer->save();
        return $loyer;
    }

    public function createMonthly(Contrat $contrat, string $month): Loyer
    {
        $loyer = $this->prepareCreate($contrat);
        $loyer->mois = Carbon::parse($month)->format('Y-m');
        $loyer->setPaid();
        $loyer->save();
        return $loyer;
    }

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

    public function avancer(int $contratId, array $periode, PaiementRepositoryInterface $paiementRepository): void
    {
        $dates = CarbonPeriod::create($periode[0], '1 month', $periode[1]);
        foreach ($dates as $date) {
            $contrat = Contrat::with('operation.appartement')->find($contratId);
            $loyer = $this->createMonthly($contrat, $date);
            $paiementRepository->createPaiementLoyer($loyer, (int) $loyer->montant);
            $detteRepository = new DetteRepository();
            $detteRepository->storeForRent($loyer, $contrat);
        }
    }
}
