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
        $types = TypeDepense::get();
        return TypeResource::collection($types);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostTypeRequest $request): JsonResponse
    {
        $type = TypeDepense::make($request->validated());
        $type->save();
        return response()->json("Le type de dépense $type->nom a été crée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeDepense $typeDepense): JsonResource
    {
        return TypeResource::make($typeDepense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutTypeRequest $request, TypeDepense $typeDepense): JsonResponse
    {
        $typeDepense->update($request->validated());
        return response()->json("Le type de dépense $typeDepense->nom a été modifié avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeDepense $typeDepense)
    {
        $typeDepense->delete();
        return response()->json("Le type de dépense $typeDepense->nom a été supprimé avec succès.");
    }
}
