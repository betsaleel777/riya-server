<?php

namespace App\Interfaces;

use App\Models\Achat;
use App\Models\Loyer;
use App\Models\Paiement;

interface PaiementRepositoryInterface
{
    public function getByType(int $payableId, string $type): Achat;
    public function createPaiement(int $payable_id, string $payable_type, int $montant): Paiement;
    public function createPaiementLoyer(Loyer $loyer, int $montant): Paiement;
    public static function dashboard(): array;
}
