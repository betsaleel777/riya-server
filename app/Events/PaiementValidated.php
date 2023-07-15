<?php

namespace App\Events;

use App\Models\Paiement;
use Illuminate\Foundation\Events\Dispatchable;

class PaiementValidated
{
    use Dispatchable;
    /**
     * Create a new event instance.
     */
    public function __construct(public Paiement $paiement)
    {
    }
}
