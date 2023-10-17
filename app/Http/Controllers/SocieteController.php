<?php

namespace App\Http\Controllers;

use App\Http\Requests\Societe\StoreRequest;
use App\Http\Requests\Societe\UpdateRequest;
use App\Http\Resources\SocieteResource;
use App\Models\Societe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class SocieteController extends Controller
{

    public function index(): JsonResource | JsonResponse
    {
        $societes = Societe::get();
        $societe = $societes->first();
        return $societes->isEmpty() ? response()->json('no societe') : SocieteResource::make($societe);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $request->validated();
        $societe = Societe::make($request->all());
        $societe->save();
        $societe->addMediaFromRequest('image')->toMediaCollection('logo');
        return response()->json("La société $societe->raison_sociale a été enregistré avec succès.");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Societe $societe, UpdateRequest $request): JsonResponse
    {
        $request->validated();
        $societe->update($request->all());
        if ($request->hasFile('image')) {
            $societe->addMediaFromRequest('image')->toMediaCollection('logo');
        }
        return response()->json("Les informations de la société ont bien été modifiés.");
    }
}
