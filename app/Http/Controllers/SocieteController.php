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
        $this->authorize('viewAny', Societe::class);
        $societes = Societe::get()->first();
        return $societes->isEmpty() ? response()->json('no societe') : SocieteResource::make($societes);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $this->authorize('create', Societe::class);
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
        $this->authorize('update', Societe::class);
        $request->validated();
        $societe->update($request->all());
        if ($request->hasFile('image')) {$societe->addMediaFromRequest('image')->toMediaCollection('logo');}
        return response()->json("Les informations de la société ont bien été modifiés.");
    }
}
