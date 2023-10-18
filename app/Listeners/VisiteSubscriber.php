<?php

namespace App\Listeners;

use App\Events\BailProcessing;

class VisiteSubscriber
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    public function handleBailProcessing(BailProcessing $event): void
    {
        $appartement = $event->visite->load('appartement')->appartement;
        $appartement->isBusy() ?: $appartement->setBusy();
    }

    public function subscribe(): array
    {
        return [
            BailProcessing::class => 'handleBailProcessing',
        ];
    }
}
