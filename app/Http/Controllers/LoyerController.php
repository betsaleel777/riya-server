<?php

namespace App\Http\Controllers;

use App\Events\LoyerValidated;
use App\Http\Resources\LoyerListResource;
use App\Http\Resources\LoyerResource;
use App\Http\Resources\LoyerValidationResource;
use App\Interfaces\PaiementRepositoryInterface;
use App\Models\Loyer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class LoyerController extends Controller
{

    public function __construct(private PaiementRepositoryInterface $paiementRepository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $loyers = Loyer::with('client', 'bien')->get();
        return LoyerListResource::collection($loyers);
    }

    public function getPending(): JsonResource
    {
        $loyers = Loyer::select('id', 'code', 'montant', 'created_at', 'contrat_id')
            ->with('client:personnes.id,nom_complet', 'bien:appartements.id,nom')->pending()->get();
        return LoyerValidationResource::collection($loyers);
    }

    /**
     * Display the specified resource.
     */
    public function show(Loyer $loyer): JsonResource
    {
        $loyer->load('bien:appartements.id,nom,ville,quartier,superficie,montant_location,cout_achat',
            'client:personnes.id,nom_complet,telephone,ville,quartier,civilite');
        return LoyerResource::make($loyer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loyer $loyer)
    {
        //
    }

    public function valider(Loyer $loyer): JsonResponse
    {
        LoyerValidated::dispatch($loyer);
        return response()->json("Le paiement du $loyer->code a été validé avec succès.");
    }

    public function encaisser(Loyer $loyer): JsonResponse
    {
        $loyer->setPending();
        $this->paiementRepository->createPaiementLoyer($loyer);
        return response()->json("Le loyer $loyer->code a été encaissé avec succès.");
    }
}
