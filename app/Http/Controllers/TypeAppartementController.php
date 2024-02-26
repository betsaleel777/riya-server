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
        $this->authorize('viewAny', TypeAppartement::class);
        return TypeResource::collection(TypeAppartement::get());
    }

    public function show(TypeAppartement $appartements_type): JsonResource
    {
        $this->authorize('view', TypeAppartement::class);
        return TypeResource::make($appartements_type);
    }

    public function store(StoreTypeRequest $request)
    {
        $this->authorize('create', TypeAppartement::class);
        $request->validated();
        $type = TypeAppartement::make($request->all());
        $type->save();
        return response()->json("Le type d'appartement $type->nom a été créer avec succès");
    }

    public function update(TypeAppartement $appartements_type, UpdateTypeRequest $request)
    {
        $this->authorize('update', TypeAppartement::class);
        $request->validated();
        $appartements_type->nom = $request->nom;
        $appartements_type->save();
        return response()->json("Le type d'appartement a été modifié avec succès");
    }

    public function destroy(TypeAppartement $appartements_type)
    {
        $this->authorize('delete', TypeAppartement::class);
        $appartements_type->delete();
        return response()->json("Le type d'appartement $appartements_type->nom a été définitivement supprimé");
    }
}
