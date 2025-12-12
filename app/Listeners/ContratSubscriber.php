<?php

namespace App\Listeners;

use App\Events\ContratAborted;
use App\Events\ContratAchatCreated;
use App\Events\ContratBailCreated;
use App\Interfaces\AchatRepositoryInterface;
use App\Interfaces\DetteRepositoryInterface;
use App\Interfaces\VisiteRepositoryInterface;
use App\Models\Achat;
use App\Models\Visite;
use RuntimeException;

class ContratSubscriber
{
    public function __construct(
        public DetteRepositoryInterface $detteRepository,
        public AchatRepositoryInterface $achatRepository,
        public VisiteRepositoryInterface $visiteRepository
    ) {}

    public function handleAchatCreated(ContratAchatCreated $event): void
    {
        $event->paiement->setValide();
        $this->achatRepository->checkUptodate($event->achat)
            ? $event->contrat->setUptodate() : $event->contrat->setNotuptodate();
        $this->detteRepository->storeForPayement($event->paiement, $event->contrat);
    }

    public function handleBailCreated(ContratBailCreated $event): void
    {
        $this->detteRepository->storeForRental($event->contrat);
    }

    public function handleContratAborted(ContratAborted $event): void
    {
        $event->contrat->loadMissing('operation');
        $operation = $event->contrat->operation;
        match (true) {
            $operation instanceof Achat => $this->achatRepository->freeBien($operation),
            $operation instanceof Visite => $this->visiteRepository->freeBien($operation),
            default => throw new RuntimeException('Operation type not found'),
        };
    }

    public function subscribe(): array
    {
        return [
            ContratAchatCreated::class => 'handleAchatCreated',
            ContratBailCreated::class => 'handleBailCreated',
            ContratAborted::class => 'handleContratAborted',
        ];
    }
}
