<?php

namespace App\Http\Controllers;

use App\Http\Resources\DetteListResource;
use App\Http\Resources\DetteResource;
use App\Models\Dette;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class DetteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $dettes = Dette::with('origine.payable')->get();
        return DetteListResource::collection($dettes);
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
