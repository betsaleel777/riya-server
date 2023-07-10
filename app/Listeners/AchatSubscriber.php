<?php

namespace App\Listeners;

use App\Events\AchatCreated;

class AchatSubscriber
{
    /**
     * Handle the event.
     */
    public function handleAchatCreated(AchatCreated $event): void
    {
        $bien = $event->achat->load('bien')->bien;
        $bien->setBusy();
    }

    public function subscribe(): array
    {
        return [
            AchatCreated::class => 'handleAchatCreated',
        ];
    }
}
