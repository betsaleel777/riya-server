<?php

namespace App\Events;

use App\Models\Contrat;
use Illuminate\Foundation\Events\Dispatchable;

class ContratBailCreated
{
    use Dispatchable;
    /**
     * Create a new event instance.
     */
    public function __construct(public Contrat $contrat)
    {
    }
}
