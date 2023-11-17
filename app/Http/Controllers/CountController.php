<?php

namespace App\Http\Controllers;

use App\Models\Appartement;
use App\Models\Depense;
use App\Models\Personne;
use App\Models\Proprietaire;
use App\Models\Terrain;
use App\Models\Visite;
use Illuminate\Http\JsonResponse;

class CountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function societe(): JsonResponse
    {
        $biens = Terrain::count() + Appartement::count();
        return response()->json(['clients' => Personne::count(), 'proprietaires' => Proprietaire::count(), 'biens' => $biens]);
    }

    public function dashboard(): JsonResponse
    {
        $biens = Appartement::count() + Terrain::count();
        $taux = $biens ? ((Appartement::busy()->count() + Terrain::busy()->count()) / $biens) * 100 : 0;
        return response()->json([
            'clients' => Personne::count(),
            'locataires' => Personne::has('contratsBail')->count(),
            'biens' => $biens,
            'visites' => Visite::count(),
            'taux' => round($taux, 2),
            'depenses' => (int) Depense::sum('montant'),
        ]);
    }
}
