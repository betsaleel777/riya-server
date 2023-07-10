<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appartement\StoreTypeRequest;
use App\Http\Requests\Appartement\UpdateTypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\TypeAppartement;
use Illuminate\Http\Resources\Json\JsonResource;

class TypeAppartementController extends Controller
{
    public function index(): JsonResource
    {
        $types = TypeAppartement::get();
        return TypeResource::collection($types);
    }

    public function show(TypeAppartement $appartements_type): JsonResource
    {
        return TypeResource::make($appartements_type);
    }

    public function store(StoreTypeRequest $request)
    {
        $request->validated();
        $type = TypeAppartement::make($request->all());
        $type->save();
        return response()->json("Le type d'appartement $type->nom a été créer avec succès");
    }

    public function update(TypeAppartement $appartements_type, UpdateTypeRequest $request)
    {
        $request->validated();
        $appartements_type->nom = $request->nom;
        $appartements_type->save();
        return response()->json("Le type d'appartement a été modifié avec succès");
    }

    public function destroy(TypeAppartement $appartements_type)
    {
        $appartements_type->delete();
        return response()->json("Le type d'appartement $appartements_type->nom a été définitivement supprimé");
    }
}
