<?php

namespace App\Http\Controllers;

use App\Events\LoyerValidated;
use App\Http\Requests\Loyer\LoyerPatchRequest;
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
        $loyers = Loyer::select('id', 'code', 'montant', 'created_at', 'contrat_id')->with('client:personnes.id,nom_complet', 'bien:appartements.id,nom', 'client.avatar:id,model_id,model_type,disk,file_name')->pending()->get();
        return LoyerValidationResource::collection($loyers);
    }

    /**
     * Display the specified resource.
     */
    public function show(Loyer $loyer): JsonResource
    {
        $loyer->load('bien:appartements.id,nom,ville,quartier,superficie,montant_location,cout_achat',
            'client:personnes.id,nom_complet,telephone,ville,quartier,civilite', 'client.avatar:id,model_id,model_type,disk,file_name');
        return LoyerResource::make($loyer);
    }

    public function valider(Loyer $loyer): JsonResponse
    {
        LoyerValidated::dispatch($loyer);
        return response()->json("Le paiement du $loyer->code a été validé avec succès.");
    }

    public function encaisser(LoyerPatchRequest $request)
    {
        $request->validated();
        $loyer = Loyer::find($request->query('id'));
        $this->paiementRepository->createPaiementLoyer($loyer, (int) $request->query('montant'));
        return response()->json("Le loyer $loyer->code a été encaissé avec succès.");
    }
}
