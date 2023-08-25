<?php

namespace App\Events;

use App\Models\Achat;
use App\Models\Contrat;
use App\Models\Loyer;
use App\Models\Paiement;
use Illuminate\Foundation\Events\Dispatchable;

class ContratAchatCreated
{
    use Dispatchable;
    /**
     * Create a new event instance.
     */
    public function __construct(public Paiement $paiement, public Achat $achat, public Contrat $contrat)
    {
    }
}
