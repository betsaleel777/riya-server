<?php

namespace App\Interfaces;

use App\Http\Requests\Contrat\ContratRequest;
use App\Models\Achat;
use App\Models\Visite;

interface ContratRepositoryInterface
{
    public function getByType(int $operationId, string $type): Visite|Achat;
    public function store(ContratRequest $request): void;
}
