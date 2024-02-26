<?php

namespace App\Http\Controllers;

use App\Http\Resources\DetteListResource;
use App\Http\Resources\DetteResource;
use App\Http\Resources\DetteValidationResource;
use App\Models\Dette;
use App\Models\Loyer;
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
        $this->authorize('viewAny', Dette::class);
        return DetteListResource::collection(Dette::with('origine')->get());
    }

    public function getPending(): JsonResource
    {
        $this->authorize('viewPending', Dette::class);
        $dettes = Dette::with('origine')->withResponsible()->pending()->get();
        return DetteValidationResource::collection($dettes);
    }

    /**
     * Display the specified resource.
     */
    public function show(Dette $dette): JsonResource
    {
        $this->authorize('view', Dette::class);
        $dette->loadMorph('origine', [
            Visite::class => ['contrat:id,debut,commission,created_at,operation_id,operation_type'],
            Paiement::class => ['payable.contrat:id,debut,commission,created_at,operation_id,operation_type'],
            Loyer::class => ['contrat:id,debut,commission,created_at,operation_id,operation_type'],
        ]);
        return DetteResource::make($dette);
    }

    public function rembourser(Dette $dette): JsonResponse
    {
        $this->authorize('update', Dette::class);
        $dette->setPending();
        return response()->json("Le rembourssement de la dette $dette->code a bien été enregistré.");
    }

    public function valider(Dette $dette): JsonResponse
    {
        $this->authorize('valider', Dette::class);
        $dette->setPaid();
        return response()->json("Le rembourssement de la dette $dette->code a bien été validé.");
    }
}
