<?php

namespace App\Interfaces;

use App\Models\Contrat;
use App\Models\Loyer;
use App\Models\Paiement;

interface LoyerRepositoryInterface
{
    public function checkUptodate(Loyer $loyer): bool;
    public function cascadeLoyerUptodate(Loyer $loyer): void;
    public function valider(Loyer $loyer): Paiement;
    public function avancer(int $contratId, array $periode, PaiementRepositoryInterface $paiementRepository): void;
    public function create(Contrat $contrat): Loyer;
}
