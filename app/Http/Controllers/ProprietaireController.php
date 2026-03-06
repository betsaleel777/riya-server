<?php

namespace App\Http\Controllers;

use App\Http\Requests\Proprietaire\StoreProprietaireRequest;
use App\Http\Requests\Proprietaire\UpdateProprietaireRequest;
use App\Http\Resources\ProprietaireResource;
use App\Models\Proprietaire;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ProprietaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Proprietaire::class);
        return ProprietaireResource::collection(Proprietaire::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProprietaireRequest $request): JsonResponse
    {
        $this->authorize('create', Proprietaire::class);
        $request->validated();
        $proprietaire = Proprietaire::make($request->all());
        $proprietaire->genererCode();
        $proprietaire->save();
        return response()->json("Le propriétaire $proprietaire->nom_complet a été crée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Proprietaire $proprietaire): JsonResource
    {
        $this->authorize('view', Proprietaire::class);
        return ProprietaireResource::make($proprietaire);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Proprietaire $proprietaire, UpdateProprietaireRequest $request)
    {
        $this->authorize('update', Proprietaire::class);
        $request->validated();
        $proprietaire->update($request->all());
        return response()->json("Le propriétaire $proprietaire->nom_complet a été modifié avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proprietaire $proprietaire)
    {
        $this->authorize('delete', Proprietaire::class);
        $proprietaire->delete();
        return response()->json("Le propriétaire $proprietaire->nom_complet a été supprimé avec succès.");
    }
}
