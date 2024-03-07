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
                    ->selectRaw("visites.created_at,SUM(frais_dossier+montant+IFNULL(ap.montant_location*(c.mois+av.mois+f.mois),0)) as money")
                    ->leftJoin('cautions as c', 'c.visite_id', '=', 'visites.id')
                    ->leftjoin('appartements as ap', 'ap.id', '=', 'visites.appartement_id')
                    ->leftJoin('avances as av', 'av.visite_id', '=', 'visites.id')
                    ->leftJoin('frais as f', 'f.visite_id', '=', 'visites.id')
                    ->from('visites')->where('visites.status', ValidableEntityStatus::VALID->value)->groupBy('visites.id'))
            ->currentYear()->sum('money');
    }
    public static function amoutDateFilter(string $date): int
    {
        return (int) Visite::select('*')
            ->from(fn($query) =>
                $query
                    ->selectRaw("visites.created_at,SUM(frais_dossier+montant+IFNULL(ap.montant_location*(c.mois+av.mois+f.mois),0)) as money")->leftJoin('cautions as c', 'c.visite_id', '=', 'visites.id')
                    ->leftjoin('appartements as ap', 'ap.id', '=', 'visites.appartement_id')
                    ->leftJoin('avances as av', 'av.visite_id', '=', 'visites.id')
                    ->leftJoin('frais as f', 'f.visite_id', '=', 'visites.id')
                    ->from('visites')->where('visites.status', ValidableEntityStatus::VALID->value)->groupBy('visites.id'))
            ->countDateFilter($date)->sum('money');
    }

    public static function dashboard(): array
    {
        $visites = Visite::selectRaw("visites.created_at,IFNULL(ap.montant_location*c.mois,0) as caution,IFNULL(ap.montant_location*av.mois,0) as avance,IFNULL(ap.montant_location*f.mois,0) as frais")
            ->leftJoin('cautions as c', 'c.visite_id', '=', 'visites.id')
            ->leftjoin('appartements as ap', 'ap.id', '=', 'visites.appartement_id')
            ->leftJoin('avances as av', 'av.visite_id', '=', 'visites.id')
            ->leftJoin('frais as f', 'f.visite_id', '=', 'visites.id')
            ->where('visites.status', ValidableEntityStatus::VALID->value)
            ->whereBetween('visites.created_at', [Carbon::now()->startOfMonth()->subMonth(4), Carbon::now()])->groupBy('visites.id')->get()
            ->groupBy(fn($date) => Carbon::parse($date->created_at)->format('Y-m'))
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
}
