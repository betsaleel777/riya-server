<?php

namespace App\Listeners;

use App\Events\ContratAchatCreated;
use App\Events\ContratBailCreated;
use App\Repositories\AchatRepository;
use App\Repositories\DetteRepository;

class ContratSubscriber
{
    public function __construct(public DetteRepository $detteRepository, public AchatRepository $achatRepository)
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
