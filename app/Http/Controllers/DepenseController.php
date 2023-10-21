<?php

namespace App\Http\Controllers;

use App\Http\Requests\Depense\DepensePostRequest;
use App\Http\Requests\Depense\DepensePutRequest;
use App\Http\Resources\DepenseListResource;
use App\Http\Resources\DepenseShowResource;
use App\Models\Depense;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class DepenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $depenses = Depense::select('id', 'titre', 'montant', 'type_depense_id', 'created_at', 'status')
            ->with(['type' => fn(BelongsTo $query) => $query->select('id', 'nom')])->get();
        return DepenseListResource::collection($depenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepensePostRequest $request): JsonResponse
    {
        $depense = Depense::make($request->validated());
        $depense->save();
        return response()->json("La dépense $depense->titre a été crée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Depense $depense): JsonResource
    {
        return DepenseShowResource::make($depense->load('type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepensePutRequest $request, Depense $depense): JsonResponse
    {
        $depense->update($request->validated());
        return response()->json("La dépense a été modifiée avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Depense $depense): JsonResponse
    {
        $depense->delete();
        return response()->json("La dépense $depense->titre a été supprimée avec succès.");
    }
}
