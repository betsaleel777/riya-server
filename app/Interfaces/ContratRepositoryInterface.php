<?php

namespace App\Interfaces;

use App\Http\Requests\Contrat\ContratRequest;
use App\Models\Achat;
use App\Models\Contrat;
use App\Models\Visite;
use Illuminate\Support\Collection;

interface ContratRepositoryInterface
{
    public function getByType(int $operationId, string $type): Visite|Achat;
    public function store(ContratRequest $request): void;
    public function visiteUpdated(Contrat $contrat, int $montant): string;
    public function achatUpdated(Contrat $contrat, int $montant): string;
    /** @return Collection<int, array<string, mixed>> */
    public function getByProprietaire(int $proprietaireId): Collection;
}
