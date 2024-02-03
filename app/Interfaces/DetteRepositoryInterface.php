<?php

namespace App\Interfaces;

use App\Models\Contrat;
use App\Models\Loyer;
use App\Models\Paiement;

interface DetteRepositoryInterface
{
    public function storeForRental(Contrat $contrat): void;
    public function storeForRent(Loyer $loyer, Contrat $contrat): void;
    public function storeForPayement(Paiement $paiement, Contrat $contrat): void;
}
