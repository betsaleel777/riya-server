<?php

namespace App\Http\Controllers;

use App\Events\PaiementValidated;
use App\Http\Requests\Achat\PaiementRequest;
use App\Http\Resources\PaiementResource;
use App\Models\Paiement;
use App\Repositories\PaiementRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaiementController extends Controller
{
    public function __construct(private PaiementRepository $paiementRepository)
    {
    }

    public function update(PaiementRequest $request, Paiement $paiement): JsonResponse
    {
        $request->validated();
        $paiement->update($request->all());
        $paiement->load('payable');
        $payable = $paiement->payable;
        return response()->json("Le montant du paiement $paiement->code concernant
        l'achat $payable->code a été modifié avec succès.");
    }

    public function store(PaiementRequest $request): JsonResponse
    {
        $request->validated();
        $paiement = $this->paiementRepository->createPaiement($request->payable_id, $request->payable_type, $request->montant);
        return response()->json("Le paiement $paiement->code a été effectué avec succès.");
    }

    public function show(Paiement $paiement): JsonResource
    {
        return PaiementResource::make($paiement);
    }

    public function valider(Paiement $paiement): JsonResponse
    {
        $paiement->setValide();
        PaiementValidated::dispatch($paiement);
        return response()->json("Le paiement $paiement->code a été validé avec succès.");
    }
}
