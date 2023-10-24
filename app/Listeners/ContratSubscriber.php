<?php

namespace App\Listeners;

use App\Events\ContratAchatCreated;
use App\Events\ContratBailCreated;
use App\Interfaces\AchatRepositoryInterface;
use App\Interfaces\DetteRepositoryInterface;

class ContratSubscriber
{
    public function __construct(public DetteRepositoryInterface $detteRepository, public AchatRepositoryInterface $achatRepository)
    {
    }

    public function handleAchatCreated(ContratAchatCreated $event): void
    {
        $event->paiement->setValide();
        $this->achatRepository->checkUptodate($event->achat) ? $event->contrat->setUptodate() : $event->contrat->setNotuptodate();
        $this->detteRepository->storeForPayement($event->paiement, $event->contrat);
    }

    public function handleBailCreated(ContratBailCreated $event): void
    {
        $this->detteRepository->storeForRental($event->contrat, $event->visite);
    }

    public function subscribe(): array
    {
        return [
            ContratAchatCreated::class => 'handleAchatCreated',
            ContratBailCreated::class => 'handleBailCreated',
        ];
    }
}
