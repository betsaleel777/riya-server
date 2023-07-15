<?php

namespace App\Repositories;

use App\Enums\ContratOperationType;
use App\Events\ContratBailCreated;
use App\Http\Requests\Contrat\ContratRequest;
use App\Interfaces\ContratRepositoryInterface;
use App\Models\Achat;
use App\Models\Contrat;
use App\Models\Paiement;
use App\Models\Visite;

class ContratRepository implements ContratRepositoryInterface
{
    public function __construct(private AchatRepository $achatRepository)
    {
    }

    public function getByType(int $operationId, string $type): Visite|Achat
    {
        return $type === ContratOperationType::VISITE->value ? Visite::find($operationId) : Achat::find($operationId);
    }

    public function store(ContratRequest $request): void
    {
        $contrat = Contrat::make($request->all());
        $operation = $this->getByType($request->operation_id, $request->operation_type);
        $contrat->operation()->associate($operation)->save();
        if ($operation instanceof Visite) {
            ContratBailCreated::dispatch($contrat);
        }
        if ($operation instanceof Achat) {
            $paiement = Paiement::find($request->paiement);
            $paiement->setValide();
            $this->achatRepository->checkUptodate($operation) ? $contrat->setUptodate() : $contrat->setNotuptodate();
        }
    }
}
