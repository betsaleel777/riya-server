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
        $this->authorize('viewAny', Personne::class);
        return PersonneListResource::collection(Personne::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', Personne::class);
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
        $this->authorize('view', Personne::class);
        return PersonneResource::make($personne->load('piece', 'avatar:id,model_id,model_type,disk,file_name'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Personne $personne, UpdateRequest $request)
    {
        $this->authorize('update', Personne::class);
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
        $this->authorize('delete', Personne::class);
        $personne->delete();
        return response()->json("Le client " . Str::upper($personne->nom_complet) . " a été supprimé avec succès.");
    }
}
