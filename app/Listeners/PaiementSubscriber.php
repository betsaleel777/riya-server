<?php

namespace App\Listeners;

use App\Events\PaiementValidated;
use App\Models\Achat;
use App\Repositories\AchatRepository;

class PaiementSubscriber
{
    public function __construct(private AchatRepository $achatRepository)
    {
    }
    /**
     * Handle the event.
     */
    public function handleValidated(PaiementValidated $event): void
    {
        $event->paiement->setValide();
        $payable = $event->paiement->load('payable')->payable;
        match (true) {
            $payable instanceof Achat => $this->achatRepository->cascadeAchatUptodate($payable),
        };
    }

    public function subscribe(): array
    {
        return [
            PaiementValidated::class => 'handleValidated',
        ];
    }
}
