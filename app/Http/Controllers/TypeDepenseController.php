<?php

namespace App\Http\Controllers;

use App\Http\Requests\Depense\PostTypeRequest;
use App\Http\Requests\Depense\PutTypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\TypeDepense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class TypeDepenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', TypeDepense::class);
        return TypeResource::collection(TypeDepense::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostTypeRequest $request): JsonResponse
    {
        $this->authorize('create', TypeDepense::class);
        $type = TypeDepense::make($request->validated())->save();
        return response()->json("Le type de dépense $type->nom a été crée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeDepense $typeDepense): JsonResource
    {
        $this->authorize('view', TypeDepense::class);
        return TypeResource::make($typeDepense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutTypeRequest $request, TypeDepense $typeDepense): JsonResponse
    {
        $this->authorize('update', TypeDepense::class);
        $typeDepense->update($request->validated());
        return response()->json("Le type de dépense $typeDepense->nom a été modifié avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeDepense $typeDepense)
    {
        $this->authorize('delete', TypeDepense::class);
        $typeDepense->delete();
        return response()->json("Le type de dépense $typeDepense->nom a été supprimé avec succès.");
    }
}
