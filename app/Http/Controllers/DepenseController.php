<?php

namespace App\Http\Controllers;

use App\Http\Requests\Depense\DepensePostRequest;
use App\Http\Requests\Depense\DepensePutRequest;
use App\Http\Resources\DepenseListResource;
use App\Http\Resources\DepenseShowResource;
use App\Http\Resources\DepenseValidationResource;
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
        $this->authorize('viewAny', Depense::class);
        $depenses = Depense::select('id', 'titre', 'montant', 'type_depense_id', 'created_at', 'status')
            ->with(['type' => fn(BelongsTo $query) => $query->select('id', 'nom')])->get();
        return DepenseListResource::collection($depenses);
    }

    public function getPending(): JsonResource
    {
        $this->authorize('viewPending', Depense::class);
        $depenses = Depense::select('id', 'titre', 'montant', 'type_depense_id', 'created_at')
            ->with(['type' => fn(BelongsTo $query) => $query->select('id', 'nom')])->withResponsible()->pending()->get();
        return DepenseValidationResource::collection($depenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepensePostRequest $request): JsonResponse
    {
        $this->authorize('create', Depense::class);
        $depense = Depense::make($request->validated());
        $depense->save();
        return response()->json("La dépense $depense->titre a été crée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Depense $depense): JsonResource
    {
        $this->authorize('view', Depense::class);
        return DepenseShowResource::make($depense->load('type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepensePutRequest $request, Depense $depense): JsonResponse
    {
        $this->authorize('update', Depense::class);
        $depense->update($request->validated());
        return response()->json("La dépense a été modifiée avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Depense $depense): JsonResponse
    {
        $this->authorize('delete', Depense::class);
        $depense->delete();
        return response()->json("La dépense $depense->titre a été supprimée avec succès.");
    }

    public function valider(Depense $depense): JsonResponse
    {
        $this->authorize('valider', Depense::class);
        $depense->setValide();
        return response()->json("La depense $depense->titre a été validée avec succès.");
    }
}
