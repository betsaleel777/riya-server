<?php

namespace App\Interfaces;

use App\Models\Loyer;
use App\Models\Paiement;

interface LoyerRepositoryInterface
{
    public function checkUptodate(Loyer $loyer): bool;
    public function cascadeLoyerUptodate(Loyer $loyer): void;
    public function valider(Loyer $loyer): Paiement;
}
