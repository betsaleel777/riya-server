<?php

namespace App\Repositories;

use App\Enums\PaiementType;
use App\Interfaces\PaiementRepositoryInterface;
use App\Models\Achat;
use App\Models\Loyer;
use App\Models\Paiement;

class PaiementRepository implements PaiementRepositoryInterface
{

    public function getByType(int $payableId, string $type): Achat
    {
        return match ($type) {
            PaiementType::ACHAT->value => Achat::find($payableId),
            PaiementType::LOYER->value => Loyer::find($payableId)
        };
    }

    public function createPaiement(int $payable_id, string $payable_type, int $montant): Paiement
    {
        $paiement = Paiement::make(['montant' => $montant]);
        $paiement->genererCode('PAA');
        $payable = $this->getByType($payable_id, $payable_type);
        $paiement->payable()->associate($payable)->save();
        return $paiement;
    }

    public function createPaiementLoyer(Loyer $loyer): Paiement
    {
        $paiement = Paiement::make(['montant' => $loyer->montant]);
        $paiement->genererCode('PAA');
        $paiement->payable()->associate($loyer)->save();
        return $paiement;
    }
}
