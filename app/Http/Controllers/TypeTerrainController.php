<?php

namespace App\Http\Controllers;

use App\Http\Requests\Terrain\StoreTypeRequest;
use App\Http\Requests\Terrain\UpdateTypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\TypeTerrain;

class TypeTerrainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', TypeTerrain::class);
        return TypeResource::collection(TypeTerrain::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeRequest $request)
    {
        $this->authorize('create', TypeTerrain::class);
        $request->validated();
        $type = TypeTerrain::make($request->all());
        $type->save();
        return response()->json("Le type de terrain $type->nom a été créer avec succès");
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeTerrain $typeTerrain)
    {
        $this->authorize('view', TypeTerrain::class);
        return TypeResource::make($typeTerrain);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeRequest $request, TypeTerrain $typeTerrain)
    {
        $this->authorize('update', TypeTerrain::class);
        $request->validated();
        $typeTerrain->nom = $request->nom;
        $typeTerrain->save();
        return response()->json("Le type de terrain a été modifié avec succès");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeTerrain $typeTerrain)
    {
        $this->authorize('delete', TypeTerrain::class);
        $typeTerrain->delete();
        return response()->json("Le type de terrain $typeTerrain->nom a été définitivement supprimé");
    }
}
