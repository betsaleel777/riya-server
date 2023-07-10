<?php

namespace App\Http\Controllers;

use App\Models\Appartement;
use App\Models\Personne;
use App\Models\Proprietaire;
use App\Models\Terrain;
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
}
