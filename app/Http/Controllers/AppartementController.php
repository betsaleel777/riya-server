<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appartement\StoreRequest;
use App\Http\Requests\Appartement\UpdateRequest;
use App\Http\Resources\AppartementListResource;
use App\Http\Resources\AppartementResource;
use App\Models\Appartement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AppartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Appartement::class);
        return AppartementListResource::collection(Appartement::with('type', 'proprietaire')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $storeRequest)
    {
        $this->authorize('create', Appartement::class);
        $storeRequest->validated();
        $appartement = Appartement::make($storeRequest->all());
        $appartement->genererCode();
        $appartement->save();
        return response()->json('L\'appartement ' . Str::upper($appartement->nom) . ' a été enregistré avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appartement $appartement): JsonResource
    {
        $this->authorize('view', Appartement::class);
        return AppartementResource::make($appartement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Appartement $appartement, UpdateRequest $request): JsonResponse
    {
        $this->authorize('update', Appartement::class);
        $request->validated();
        $appartement->update($request->except('reference'));
        return response()->json("L'appartement a été modifié avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appartement $appartement)
    {
        $this->authorize('delete', Appartement::class);
        $appartement->delete();
        return response()->json("Le client " . Str::upper($appartement->nom) . " a été supprimé avec succès.");
    }
}
