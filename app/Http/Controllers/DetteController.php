<?php

namespace App\Http\Controllers;

use App\Http\Resources\DetteListResource;
use App\Http\Resources\DetteResource;
use App\Http\Resources\DetteValidationResource;
use App\Models\Dette;
use App\Models\Paiement;
use App\Models\Visite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class DetteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $dettes = Dette::with('origine')->get();
        return DetteListResource::collection($dettes);
    }

    public function getPending(): JsonResource
    {
        $dettes = Dette::with('origine', 'responsable:id,user_type,user_id,auditable_type,auditable_id')->pending()->get();
        return DetteValidationResource::collection($dettes);
    }

    /**
     * Display the specified resource.
     */
    public function show(Dette $dette): JsonResource
    {
        $dette->loadMorph('origine', [
            Visite::class => ['contrat:id,debut,commission,created_at,operation_id,operation_type'],
            Paiement::class => ['payable.contrat:id,debut,commission,created_at,operation_id,operation_type'],
        ]);
        return DetteResource::make($dette);
    }

    public function rembourser(Dette $dette): JsonResponse
    {
        $dette->setPending();
        return response()->json("Le rembourssement de la dette $dette->code a bien été enregistré.");
    }

    public function valider(Dette $dette): JsonResponse
    {
        $dette->setPaid();
        return response()->json("Le rembourssement de la dette $dette->code a bien été validé.");
    }
}
