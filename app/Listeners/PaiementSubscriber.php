<?php

namespace App\Listeners;

use App\Events\PaiementValidated;
use App\Interfaces\AchatRepositoryInterface;
use App\Interfaces\DetteRepositoryInterface;
use App\Models\Achat;

class PaiementSubscriber
{
    public function __construct(private AchatRepositoryInterface $achatRepository, private DetteRepositoryInterface $detteRepository)
    {}
    /**
     * Handle the event.
     */
    public function handleValidated(PaiementValidated $event): void
    {
        $payable = $event->paiement->load('payable.contrat')->payable;
        if ($payable instanceof Achat) {
            $this->achatRepository->cascadeAchatUptodate($payable);
            $this->detteRepository->storeForPayement($event->paiement, $payable->contrat);
        }
    }

    public function subscribe(): array
    {
        return [PaiementValidated::class => 'handleValidated'];
    }
}
