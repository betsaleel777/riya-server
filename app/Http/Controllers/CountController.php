<?php

namespace App\Http\Controllers;

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

class CountController extends Controller
{
    public function __construct(private VisiteRepositoryInterface $visiteRepository,
        private PaiementRepositoryInterface $paiementRepository) {}

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
        return response()->json([
            'clients' => Personne::count(),
            'locataires' => Personne::has('contratsBail')->count(),
            'biens' => $biens,
            'visites' => Visite::currentYear()->count(),
            'taux' => $taux,
            'depenses' => (int) Depense::currentYear()->validated()->sum('montant'),
            'remboursements' => (int) Dette::currentYear()->paid()->sum('montant'),
            'terrains' => $terrains,
            'chiffres' => $this->visiteRepository::amout() + Paiement::validated()->sum('montant'),
            'paiements' => $this->paiementRepository::dashboard(),
            'locations' => $this->visiteRepository::dashboard(),
            'contrats' => ['uptodate' => Contrat::uptodate()->count(), 'notuptodate' => Contrat::notUptodate()->count()],
            'appartements' => $appartements,
        ]);
    }

    public function pendings(): JsonResponse
    {
        $pendings = Achat::pending()->count() + Dette::pending()->count() + Loyer::pending()->count() + Depense::pending()->count() +
        Visite::pending()->count();
        return response()->json((int) $pendings);
    }

    public function depenses(CountDateRequest $request): JsonResponse
    {
        $depenses = (int) Depense::countDateFilter($request->query('date'))->sum('montant');
        return response()->json($depenses);
    }

    public function dettes(CountDateRequest $request): JsonResponse
    {
        $dettes = (int) Dette::countDateFilter($request->query('date'))->sum('montant');
        return response()->json($dettes);
    }

    public function chiffres(CountDateRequest $request): JsonResponse
    {
        return response()->json();
    }
}
