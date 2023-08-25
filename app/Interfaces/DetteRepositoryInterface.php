<?php

namespace App\Interfaces;

use App\Models\Contrat;
use App\Models\Paiement;
use App\Models\Visite;

interface DetteRepositoryInterface
{
    public function storeForRental(Contrat $contrat, Visite $visite): void;
    public function storeForPayement(Paiement $paiement, Contrat $contrat): void;
}
