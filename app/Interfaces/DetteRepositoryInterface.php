<?php

namespace App\Interfaces;

use App\Models\Contrat;
use App\Models\Loyer;
use App\Models\Paiement;
use App\Models\Visite;

interface DetteRepositoryInterface
{
    public function storeForRental(Contrat $contrat): void;
    public function storeForRent(Loyer $loyer, Contrat $contrat): void;
    public function storeForPayement(Paiement $paiement, Contrat $contrat): void;
    public function cascadeUpdateFromVisite(Visite $visite): void;
}
