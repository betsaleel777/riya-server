<?php

namespace App\Http\Controllers;

use App\Http\Requests\Terrain\StoreRequest;
use App\Http\Requests\Terrain\UpdateRequest;
use App\Http\Resources\TerrainListResource;
use App\Http\Resources\TerrainResource;
use App\Models\Terrain;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TerrainController extends Controller
{
    public function index(): JsonResource
    {
        $query = Terrain::with('type', 'proprietaire');
        $terrain = Auth::user()->can('viewAny', Terrain::class) ? $query->get() : $query->owner()->get();
        return TerrainListResource::collection($terrain);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $this->authorize('create', Terrain::class);
        $request->validated();
        $terrain = Terrain::make($request->all());
        $terrain->genererCode();
        $terrain->save();
        return response()->json("Le terrain " . Str::upper($terrain->nom) . " a été crée avec succès.");
    }

    public function show(Terrain $terrain): JsonResource
    {
        $this->authorize('view', $terrain);
        return TerrainResource::make($terrain);
    }

    public function update(UpdateRequest $updateRequest, Terrain $terrain): JsonResponse
    {
        $this->authorize('update', $terrain);
        $updateRequest->validated();
        $terrain->update($updateRequest->all());
        return response()->json("Le terrain a été modifié avec succès.");
    }

    public function destroy(Terrain $terrain)
    {
        $this->authorize('delete', $terrain);
        $terrain->delete();
        return response()->json("Le terrain $terrain->nom a été supprimé avec succès.");
    }
}
