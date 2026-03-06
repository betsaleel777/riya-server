<?php

namespace App\Repositories;

use App\Enums\ValidableEntityStatus;
use App\Events\BailProcessing;
use App\Interfaces\VisiteRepositoryInterface;
use App\Models\Visite;
use Carbon\Carbon;

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

    // somme tatal d'argent encaissé pour les visite simple et location validées
    public static function amout(): int
    {
        return (int) Visite::select('*')
            ->from(fn($query) =>
            $query
                ->selectRaw("
                visites.visite_date,
                SUM(frais_dossier+montant+IFNULL(COALESCE(contrats.montant_location, ap.montant_location)*(c.mois+av.mois+f.mois),0)) as money")
                ->leftJoin('cautions as c', 'c.visite_id', '=', 'visites.id')
                ->leftjoin('appartements as ap', 'ap.id', '=', 'visites.appartement_id')
                ->leftJoin('avances as av', 'av.visite_id', '=', 'visites.id')
                ->leftJoin('frais as f', 'f.visite_id', '=', 'visites.id')
                ->leftJoin('contrats', fn($join) => $join->on('contrats.operation_id', '=', 'visites.id')
                    ->where('contrats.operation_type', '=', Visite::class))
                ->from('visites')
                ->where('visites.status', ValidableEntityStatus::VALID->value)
                ->whereBetween('visites.visite_date', [now()->startOfYear(), now()->endOfYear()])
                ->groupBy('visites.id', 'visites.created_at', 'visites.visite_date'))
            ->sum('money');
    }

    public static function amoutDateFilter(string $date): int
    {
        $dates = explode(',', $date);
        return (int) Visite::select('*')
            ->from(fn($query) =>
            $query
                ->selectRaw("
                visites.visite_date,
                SUM(frais_dossier+montant+IFNULL(COALESCE(contrats.montant_location, ap.montant_location)*(c.mois+av.mois+f.mois),0)) as money")
                ->leftJoin('cautions as c', 'c.visite_id', '=', 'visites.id')
                ->leftjoin('appartements as ap', 'ap.id', '=', 'visites.appartement_id')
                ->leftJoin('avances as av', 'av.visite_id', '=', 'visites.id')
                ->leftJoin('frais as f', 'f.visite_id', '=', 'visites.id')
                ->leftJoin('contrats', fn($join) => $join->on('contrats.operation_id', '=', 'visites.id')
                    ->where('contrats.operation_type', '=', Visite::class))
                ->from('visites')
                ->where('visites.status', ValidableEntityStatus::VALID->value)
                ->when(count($dates) === 2, fn($q) => $q->whereBetween('visites.visite_date', [$dates[0], $dates[1]]))
                ->when(count($dates) === 1, fn($q) => $q->whereDate('visites.visite_date', $dates[0]))
                ->groupBy('visites.id', 'visites.visite_date'))
            ->sum('money');
    }

    public static function entreesDateFilter(array $dates): int
    {
        return (int) Visite::select('*')
            ->from(fn($query) =>
            $query->selectRaw("
                visites.visite_date,
                SUM(frais_dossier+montant+
                IFNULL(
                COALESCE(contrats.montant_location, ap.montant_location)*(c.mois+av.mois)*(1-contrats.commission/100) + 
                COALESCE(contrats.montant_location, ap.montant_location)*f.mois,0)) as money")
                ->leftJoin('cautions as c', 'c.visite_id', '=', 'visites.id')
                ->leftjoin('appartements as ap', 'ap.id', '=', 'visites.appartement_id')
                ->leftJoin('avances as av', 'av.visite_id', '=', 'visites.id')
                ->leftJoin('frais as f', 'f.visite_id', '=', 'visites.id')
                ->leftJoin('contrats', fn($join) => $join->on('contrats.operation_id', '=', 'visites.id')
                    ->where('contrats.operation_type', '=', Visite::class))
                ->from('visites')
                ->where('visites.status', ValidableEntityStatus::VALID->value)
                ->whereBetween('visites.visite_date', [$dates[0], $dates[1]])
                ->groupBy('visites.id', 'visites.visite_date'))
            ->sum('money');
    }

    public static function dashboard(): array
    {
        $visites = Visite::selectRaw("
        visites.visite_date,
        IFNULL(COALESCE(contrats.montant_location, ap.montant_location)*c.mois,0) as caution,
        IFNULL(COALESCE(contrats.montant_location, ap.montant_location)*av.mois,0) as avance,
        IFNULL(COALESCE(contrats.montant_location, ap.montant_location)*f.mois,0) as frais")
            ->leftJoin('cautions as c', 'c.visite_id', '=', 'visites.id')
            ->leftjoin('appartements as ap', 'ap.id', '=', 'visites.appartement_id')
            ->leftJoin('avances as av', 'av.visite_id', '=', 'visites.id')
            ->leftJoin('frais as f', 'f.visite_id', '=', 'visites.id')
            ->leftJoin('contrats', fn($join) => $join->on('contrats.operation_id', '=', 'visites.id')
                ->where('contrats.operation_type', '=', Visite::class))
            ->where('visites.status', ValidableEntityStatus::VALID->value)
            ->whereBetween('visites.visite_date', [Carbon::now()->startOfMonth()->subMonth(4), Carbon::now()])
            ->groupBy('visites.id', 'visites.visite_date')
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->visite_date)->format('Y-m'))
            ->map(fn($item) => Collect([
                'caution' => $item->sum('caution'),
                'avance' => $item->sum('avance'),
                'frais' => $item->sum('frais'),
            ]));
        return [
            'dates' => $visites->keys(),
            'cautions' => $visites->map(fn($item) => $item->get('caution', 0))->values(),
            'avances' => $visites->map(fn($item) => $item->get('avance', 0))->values(),
            'frais' => $visites->map(fn($item) => $item->get('frais', 0))->values(),
        ];
    }

    public function freeBien(Visite $visite): void
    {
        $visite->loadMissing('appartement');
        $visite->appartement->setFree();
    }
}
