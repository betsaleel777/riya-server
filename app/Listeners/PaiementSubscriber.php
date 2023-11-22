<?php

namespace App\Listeners;

use App\Events\LoyerValidated;
use App\Events\PaiementValidated;
use App\Models\Achat;
use App\Repositories\AchatRepository;
use App\Repositories\DetteRepository;

class PaiementSubscriber
{
    public function __construct(private AchatRepository $achatRepository, private DetteRepository $detteRepository)
    {
    }
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

    public function handleLoyerValidated(LoyerValidated $event): void
    {
        $event->loyer->load('paiement', 'contrat');
        $event->loyer->setPaid();
        $event->loyer->paiement->setValide();
        $this->detteRepository->storeForPayement($event->loyer->paiement, $event->loyer->contrat);
    }

    public function subscribe(): array
    {
        return [
            PaiementValidated::class => 'handleValidated',
            LoyerValidated::class => 'handleLoyerValidated',
        ];
    }
}
