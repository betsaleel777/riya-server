<?php

namespace App\Listeners;

use App\Events\AchatCreated;
use App\Events\AchatDeleted;

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

    public function handleAchatDeleted(AchatDeleted $event): void
    {
        $bien = $event->achat->load('bien')->bien;
        $bien->setFree();
    }

    public function subscribe(): array
    {
        return [
            AchatCreated::class => 'handleAchatCreated',
            AchatDeleted::class => 'handleAchatDeleted'
        ];
    }
}
