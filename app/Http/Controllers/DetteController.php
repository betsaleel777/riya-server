<?php

namespace App\Http\Controllers;

use App\Http\Resources\DetteListResource;
use App\Http\Resources\DetteResource;
use App\Http\Resources\DetteValidationResource;
use App\Models\Dette;
use App\Models\Paiement;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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
        $dettes = Dette::select('id', 'code', 'montant', 'origine_id', 'origine_type', 'created_at')
            ->with(['origine' => fn(MorphTo $morphTo) =>
                $morphTo->morphWith([
                    Visite::class => ['appartement:id,nom,proprietaire_id', 'appartement.proprietaire:id,nom_complet,telephone'],
                    Paiement::class => ['payable:id,code,bien_id,bien_type', 'payable.bien:id,nom,proprietaire_id',
                        'payable.bien.proprietaire:id,nom_complet,telephone'],
                ]),
            ])->pending()->get();
        return DetteValidationResource::collection($dettes);
    }

    /**
     * Display the specified resource.
     */
    public function show(Dette $dette): JsonResource
    {
        $dette->load('origine.contrat');
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
