<?php

namespace App\Repositories;

use App\Enums\PaiementType;
use App\Interfaces\PaiementRepositoryInterface;
use App\Models\Achat;
use App\Models\Loyer;
use App\Models\Paiement;
use Carbon\Carbon;

class PaiementRepository implements PaiementRepositoryInterface
{

    public function getByType(int $payableId, string $type): Achat
    {
        return match ($type) {
            PaiementType::ACHAT->value => Achat::find($payableId),
            PaiementType::LOYER->value => Loyer::find($payableId)
        };
    }

    public function createPaiement(int $payable_id, string $payable_type, int $montant): Paiement
    {
        $paiement = Paiement::make(['montant' => $montant]);
        $paiement->genererCode('PAA');
        $payable = $this->getByType($payable_id, $payable_type);
        $paiement->payable()->associate($payable)->save();
        return $paiement;
    }

    public function createPaiementLoyer(Loyer $loyer, int $montant): Paiement
    {
        $paiement = Paiement::make(['montant' => $montant]);
        $paiement->genererCode('PAL');
        $paiement->payable()->associate($loyer)->save();
        return $paiement;
    }

    public static function dashboard(): array
    {
        $paiements = Paiement::select('created_at', 'montant', 'payable_type')->validated()
            ->whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth(5), Carbon::now()])->get()
            ->groupBy(fn($date) => Carbon::parse($date->created_at)->format('Y-m'))
            ->map(fn($item) => $item->mapToGroups(fn($item) => [$item->payable_type => $item->montant]))
            ->map(fn($item) => $item->map(fn($item) => $item->sum()));
        return [
            'dates' => $paiements->keys(),
            'achats' => $paiements->map(fn($item) => $item->get('App\Models\Achat', 0))->values(),
            'loyers' => $paiements->map(fn($item) => $item->get('App\Models\Loyer', 0))->values(),
        ];
    }
}
