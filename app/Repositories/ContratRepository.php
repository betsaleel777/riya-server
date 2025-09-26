<?php

namespace App\Repositories;

use App\Enums\ContratOperationType;
use App\Events\ContratAchatCreated;
use App\Events\ContratBailCreated;
use App\Http\Requests\Contrat\ContratRequest;
use App\Interfaces\ContratRepositoryInterface;
use App\Models\Achat;
use App\Models\Contrat;
use App\Models\Paiement;
use App\Models\Visite;
use RuntimeException;

class ContratRepository implements ContratRepositoryInterface
{
    public function __construct(private AchatRepository $achatRepository)
    {
    }

    public function getByType(int $operationId, string $type): Visite | Achat
    {
        return match ($type) {
            ContratOperationType::VISITE->value => Visite::with('appartement')->find($operationId),
            ContratOperationType::ACHAT->value => Achat::with('bien')->find($operationId),
            default => throw new RuntimeException('Operation type not found'),
        };
    }

    public function store(ContratRequest $request): void
    {
        $contrat = Contrat::make($request->all());
        $operation = $this->getByType($request->operation_id, $request->operation_type);
        if ($operation instanceof Visite) {
            $contrat->montant_location = $operation->appartement->montant_location;
        }
        if ($operation instanceof Achat) {
            $contrat->cout_achat = $operation->bien->cout_achat;
        }
        $contrat->operation()->associate($operation)->save();
        if ($operation instanceof Visite) {
            ContratBailCreated::dispatch($contrat, $operation);
        }
        if ($operation instanceof Achat) {
            $paiement = Paiement::find($request->paiement);
            ContratAchatCreated::dispatch($paiement, $operation, $contrat);
        }
    }
}
