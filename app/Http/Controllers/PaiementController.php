<?php

namespace App\Http\Controllers;

use App\Http\Requests\Achat\PaiementRequest;
use App\Http\Resources\PaiementResource;
use App\Models\Paiement;
use App\Repositories\AchatRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class PaiementController extends Controller
{
    public function __construct(private AchatRepository $achatRepository)
    {
    }

    public function update(PaiementRequest $request, Paiement $paiement): JsonResponse
    {
        $request->validated();
        $paiement->update($request->all());
        $paiement->load('achat');
        $achat = $paiement->achat;
        return response()->json("Le montant du paiement $paiement->code concernant
        l'achat $achat->code a été modifié avec succès.");
    }

    public function store(PaiementRequest $request): JsonResponse
    {
        $request->validated();
        $paiement = $this->achatRepository->createPaiement($request->achat_id, $request->montant);
        return response()->json("Le paiement $paiement->code a été effectué avec succès.");
    }

    public function show(Paiement $paiement): JsonResource
    {
        return PaiementResource::make($paiement);
    }

    public function getByAchat(int $id): JsonResource
    {
        $paiements = Paiement::where('achat_id', $id)->get();
        return PaiementResource::collection($paiements);
    }
}
