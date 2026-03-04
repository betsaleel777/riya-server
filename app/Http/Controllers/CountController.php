<?php

namespace App\Http\Controllers;

use App\Enums\ContratState;
use App\Enums\PayableStatus;
use App\Enums\ValidableEntityStatus;
use App\Http\Requests\Dashboard\CountDateRequest;
use App\Interfaces\PaiementRepositoryInterface;
use App\Interfaces\VisiteRepositoryInterface;
use App\Models\Achat;
use App\Models\Appartement;
use App\Models\Contrat;
use App\Models\Depense;
use App\Models\Dette;
use App\Models\Loyer;
use App\Models\Paiement;
use App\Models\Personne;
use App\Models\Proprietaire;
use App\Models\Terrain;
use App\Models\Visite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CountController extends Controller
{
    public function __construct(
        private VisiteRepositoryInterface $visiteRepository,
        private PaiementRepositoryInterface $paiementRepository
    ) {}

    public function societe(): JsonResponse
    {
        $biens = Terrain::count() + Appartement::count();
        return response()->json(['clients' => Personne::count(), 'proprietaires' => Proprietaire::count(), 'biens' => $biens]);
    }

    public function dashboard(): JsonResponse
    {
        $terrains = Terrain::count();
        $appartements = Appartement::count();
        $biens = $appartements + $terrains;
        $taux = $biens ? round(((Appartement::busy()->count() + Terrain::busy()->count()) / $biens) * 100, 2) : 0;
        $dettes = Dette::currentYear()->paid()->sum('montant');
        return response()->json([
            'clients' => Personne::count(),
            'locataires' => Personne::has('contratsBail')->count(),
            'biens' => $biens,
            'visites' => Visite::currentYear()->count(),
            'taux' => $taux,
            'depenses' => (int) Depense::currentYear()->validated()->sum('montant'),
            'remboursements' => (int) $dettes,
            'terrains' => $terrains,
            'chiffres' => $this->visiteRepository::amout() + Paiement::validated()->currentYear()->sum('montant') - $dettes,
            'paiements' => $this->paiementRepository::dashboard(),
            'locations' => $this->visiteRepository::dashboard(),
            'contrats' => ['uptodate' => Contrat::uptodate()->count(), 'notuptodate' => Contrat::notUptodate()->count()],
            'appartements' => $appartements,
        ]);
    }

    public function pendings(): JsonResponse
    {
        $pendings = (int) Achat::pending()->count() + (int) Dette::pending()->count() + (int) Loyer::pending()->count() + (int) Depense::pending()->count() + (int) Visite::pending()->count();
        return response()->json($pendings);
    }

    public function depenses(CountDateRequest $request): JsonResponse
    {
        $depenses = (int) Depense::validated()->countDateFilter($request->query('date'))->sum('montant');
        return response()->json($depenses);
    }

    public function dettes(CountDateRequest $request): JsonResponse
    {
        $dettes = (int) Dette::paid()->countDateFilter($request->query('date'))->sum('montant');
        return response()->json($dettes);
    }

    public function chiffres(CountDateRequest $request): JsonResponse
    {
        $chiffres = $this->visiteRepository::amoutDateFilter($request->query('date')) +
            (int)Paiement::validated()->countDateFilter($request->query('date'))->sum('montant') -
            (int)Dette::currentYear()->paid()->countDateFilter($request->query('date'))->sum('montant');
        return response()->json($chiffres);
    }

    public function rapport(Request $request): JsonResponse
    {
        $at = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();
        //Sous-requête pour calculer les paiements validés du mois en cours par propriétaire
        $loyersPayes = DB::table('paiements')
            ->join('loyers', function ($join) {
                $join->on('paiements.payable_id', '=', 'loyers.id')
                    ->where('paiements.payable_type', '=', Loyer::class);
            })
            ->join('contrats', 'loyers.contrat_id', '=', 'contrats.id')
            ->join('visites', function ($join) {
                $join->on('contrats.operation_id', '=', 'visites.id')
                    ->where('contrats.operation_type', '=', Visite::class);
            })
            ->join('appartements', 'visites.appartement_id', '=', 'appartements.id')
            ->where('paiements.status', ValidableEntityStatus::VALID->value)
            ->whereBetween('loyers.created_at', [$at->copy()->startOfMonth(), $at->copy()->endOfMonth()])
            ->select('appartements.proprietaire_id', DB::raw('SUM(paiements.montant) as paye'))
            ->groupBy('appartements.proprietaire_id');

        // Sous-requête pour calculer les remboursements du mois en cours par propriétaire
        $dettesLoyer = DB::table('dettes')
            ->join('loyers', function ($join) {
                $join->on('dettes.origine_id', '=', 'loyers.id')
                    ->where('dettes.origine_type', '=', Loyer::class);
            })
            ->join('contrats', 'loyers.contrat_id', '=', 'contrats.id')
            ->join('visites', function ($join) {
                $join->on('contrats.operation_id', '=', 'visites.id')
                    ->where('contrats.operation_type', '=', Visite::class);
            })
            ->join('appartements', 'visites.appartement_id', '=', 'appartements.id')
            ->where('dettes.status', PayableStatus::PAID->value)
            ->whereBetween('loyers.created_at', [$at->copy()->startOfMonth(), $at->copy()->endOfMonth()])
            ->select('appartements.proprietaire_id', DB::raw('SUM(dettes.montant) as rembourse'))
            ->groupBy('appartements.proprietaire_id');


        $rapport =  DB::table('proprietaires')
            ->join('appartements', 'proprietaires.id', '=', 'appartements.proprietaire_id')
            ->leftJoin('visites', 'appartements.id', '=', 'visites.appartement_id')
            ->leftJoin('contrats', 'visites.id', '=', 'contrats.operation_id')
            ->leftJoin('avances', 'visites.id', '=', 'avances.visite_id')
            ->leftJoinSub($loyersPayes, 'loyers_payes', fn($join) => $join->on('proprietaires.id', '=', 'loyers_payes.proprietaire_id'))
            ->leftJoinSub($dettesLoyer, 'dettes_loyer', fn($join) => $join->on('proprietaires.id', '=', 'dettes_loyer.proprietaire_id'))
            ->select('proprietaires.id', 'proprietaires.nom_complet as proprietaire')
            ->selectRaw('COUNT(DISTINCT appartements.id) as biens')
            ->selectRaw('CAST(SUM(contrats.montant_location) AS UNSIGNED) as total')
            ->selectRaw('CAST(COALESCE(loyers_payes.paye, 0) AS UNSIGNED) as paye')
            ->selectRaw('CAST(SUM(contrats.montant_location*contrats.commission/100) AS UNSIGNED) as a_rembourser')
            ->selectRaw('CAST(COALESCE(dettes_loyer.rembourse, 0) AS UNSIGNED) as rembourse')
            ->where('contrats.etat', ContratState::USING->value)
            ->whereRaw('? > DATE_ADD(contrats.debut, INTERVAL avances.mois MONTH)', [$at->format('Y-m-d')])
            ->groupBy('proprietaires.id', 'proprietaires.nom_complet', 'loyers_payes.paye', 'dettes_loyer.rembourse')
            ->get();
        return response()->json($rapport);
    }
}
