<?php

namespace App\Repositories;

use App\Events\BailProcessing;
use App\Interfaces\VisiteRepositoryInterface;
use App\Models\Visite;

class VisiteRepository implements VisiteRepositoryInterface
{
    public function emitBailProcess(int | Visite $rental): Visite
    {
        $visite = match (true) {
            $rental instanceof Visite => $rental,
            default => Visite::with('avance', 'frais', 'caution')->find($rental),
        };
        BailProcessing::dispatchIf($visite->bailProcessStarted(), $visite);
        return $visite;
    }
}
