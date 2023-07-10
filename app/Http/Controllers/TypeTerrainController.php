<?php

namespace App\Http\Controllers;

use App\Http\Requests\Terrain\StoreTypeRequest;
use App\Http\Requests\Terrain\UpdateTypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\TypeTerrain;
use Illuminate\Http\Request;

class TypeTerrainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = TypeTerrain::get();
        return TypeResource::collection($types);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeRequest $request)
    {
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
        return TypeResource::make($typeTerrain);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeRequest $request, TypeTerrain $typeTerrain)
    {
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
        $typeTerrain->delete();
        return response()->json("Le type de terrain $typeTerrain->nom a été définitivement supprimé");
    }
}
