<?php

namespace App\Listeners;

use App\Events\ContratAchatCreated;
use App\Events\ContratBailCreated;

class ContratSubscriber
{
    /**
     * Handle the event.
     */
    public function handleAchatCreated(ContratAchatCreated $event): void
    {
        $bien = $event->contrat->load('operation.bien')->operation->bien;
        $bien->setBusy();
    }

    public function handleBailCreated(ContratBailCreated $event): void
    {
        $appartement = $event->contrat->load('operation.appartement')->operation->appartement;
        $appartement->setBusy();
    }

    public function subscribe(): array
    {
        return [
            ContratAchatCreated::class => 'handleAchatCreated',
            ContratBailCreated::class => 'handleBailCreated',
        ];
    }
}
