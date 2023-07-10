<?php

namespace App\Repositories;

use App\Enums\ContratOperationType;
use App\Events\ContratAchatCreated;
use App\Events\ContratBailCreated;
use App\Http\Requests\Contrat\ContratRequest;
use App\Interfaces\ContratRepositoryInterface;
use App\Models\Achat;
use App\Models\Contrat;
use App\Models\Visite;

class ContratRepository implements ContratRepositoryInterface
{

    public function getByType(int $operationId, string $type): Visite|Achat
    {
        return $type === ContratOperationType::VISITE->value ? Visite::find($operationId) : Achat::find($operationId);
    }

    public function dispatchByType(Contrat $contrat, Visite|Achat $operation): void
    {
        match (true) {
            $operation instanceof Visite => ContratBailCreated::dispatch($contrat),
            $operation instanceof Achat => ContratAchatCreated::dispatch($contrat),
        };
    }

    public function store(ContratRequest $request): void
    {
        $contrat = Contrat::make($request->all());
        $operation = $this->getByType($request->operation_id, $request->operation_type);
        $contrat->operation()->associate($operation)->save();
        $this->dispatchByType($contrat, $operation);
    }
}
