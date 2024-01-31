<?php

namespace App\Http\Controllers;

use App\Events\PaiementValidated;
use App\Http\Requests\Achat\PaiementRequest;
use App\Http\Requests\Paiement\PaiementDirectRequest;
use App\Http\Resources\PaiementResource;
use App\Models\Achat;
use App\Models\Loyer;
use App\Models\Paiement;
use App\Repositories\PaiementRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class PaiementController extends Controller
{
    public function __construct(private PaiementRepository $paiementRepository)
    {
    }

    public function index(): JsonResource
    {
        $paiements = Paiement::get();
        return PaiementResource::collection($paiements);
    }

    public function update(PaiementRequest $request, Paiement $paiement): JsonResponse
    {
        $request->validated();
        $paiement->update($request->all());
        $paiement->load('payable');
        $payable = $paiement->payable;
        return response()->json("Le montant du paiement $paiement->code concernant l'achat $payable->code a été modifié avec succès.");
    }

    public function store(PaiementRequest $request): JsonResponse
    {
        $request->validated();
        $paiement = $this->paiementRepository->createPaiement($request->payable_id, $request->payable_type, $request->montant);
        return response()->json("Le paiement $paiement->code a été effectué avec succès.");
    }

    public function createDirect(PaiementDirectRequest $request): JsonResponse
    {
        $request->validated();
        $paiement = $this->paiementRepository->createPaiement($request->payable_id, $request->payable_type, $request->montant);
        return response()->json("Le paiement $paiement->code a été effectué avec succès.");
    }

    public function show(Paiement $paiement): JsonResource
    {
        $paiement->loadMorph('payable', [
            Loyer::class => ['client:personnes.id,nom_complet,telephone,quartier,ville',
                'bien:appartements.id,nom,montant_location,quartier'],
            Achat::class => ['personne:id,nom_complet,telephone,quartier,ville', 'bien:id,nom,cout_achat,superficie,quartier',
                'contrat:id,created_at,commission,debut', 'paiements:id,montant,payable_id,payable_type,created_at'],
        ]);
        return PaiementResource::make($paiement);
    }

    public function valider(Paiement $paiement): JsonResponse
    {
        $paiement->setValide();
        PaiementValidated::dispatch($paiement);
        return response()->json("Le paiement $paiement->code a été validé avec succès.");
    }

    public function getByPayable(int $id): JsonResource
    {
        $paiements = Paiement::where('payable_id', $id)->get();
        return PaiementResource::collection($paiements);
    }
}
