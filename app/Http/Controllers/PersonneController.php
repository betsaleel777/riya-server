<?php

namespace App\Http\Controllers;

use App\Http\Requests\Personne\StoreRequest;
use App\Http\Requests\Personne\UpdateRequest;
use App\Http\Resources\PersonneListResource;
use App\Http\Resources\PersonneResource;
use App\Models\Personne;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PersonneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $personnes = Personne::get();
        return PersonneListResource::collection($personnes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $request->validated();
        $personne = Personne::make($request->all());
        $personne->genererCode();
        $personne->save();
        $personne->addMediaFromRequest('image_piece')->toMediaCollection('piece');
        if ($request->hasFile('image_avatar')) {
            $personne->addMediaFromRequest('image_avatar')->toMediaCollection('avatar');
        }
        return response()->json('Le client ' . Str::upper($personne->nom_complet) . ' a été enregistré avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Personne $personne): JsonResource
    {
        $personne->load('piece', 'avatar');
        return PersonneResource::make($personne);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Personne $personne, UpdateRequest $request)
    {
        $request->validated();
        $personne->update($request->all());
        if ($request->hasFile('image_avatar')) {
            $personne->addMediaFromRequest('image_avatar')->toMediaCollection('avatar');
        }
        if ($request->hasFile('image_piece')) {
            $personne->addMediaFromRequest('image_piece')->toMediaCollection('piece');
        }
        return response()->json("Les informations du client ont bien été modifiées.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personne $personne)
    {
        $personne->delete();
        return response()->json("Le client " . Str::upper($personne->nom_complet) . " a été supprimé avec succès.");
    }
}
