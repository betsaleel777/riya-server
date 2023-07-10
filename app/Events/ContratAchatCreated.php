<?php

namespace App\Events;

use App\Models\Contrat;
use Illuminate\Foundation\Events\Dispatchable;

class ContratAchatCreated
{
    use Dispatchable;
    /**
     * Create a new event instance.
     */
    public function __construct(public Contrat $contrat)
    {
    }
}
