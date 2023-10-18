<?php

namespace App\Events;

use App\Models\Visite;
use Illuminate\Foundation\Events\Dispatchable;

class BailProcessing
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public Visite $visite)
    {
    }

}
