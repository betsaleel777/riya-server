<?php

namespace App\Events;

use App\Models\Achat;
use Illuminate\Foundation\Events\Dispatchable;

class AchatDeleted
{
    use Dispatchable;
    /**
     * Create a new event instance.
     */
    public function __construct(public Achat $achat)
    {
    }
}
