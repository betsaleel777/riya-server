<?php

namespace App\Http\Controllers;

use App\Http\Requests\Personne\StoreTypeRequest;
use App\Http\Requests\Personne\UpdateTypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\TypeClient;

class TypeClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = TypeClient::get();
        return TypeResource::collection($types);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeRequest $request)
    {
        $request->validated();
        $type = TypeClient::make($request->all());
        $type->save();
        return response()->json("Le type de client $type->nom a été créer avec succès");
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeClient $typeClient)
    {
        return TypeResource::make($typeClient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeRequest $request, TypeClient $typeClient)
    {
        $request->validated();
        $typeClient->nom = $request->nom;
        $typeClient->save();
        return response()->json("Le type de client a été modifié avec succès");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeClient $typeClient)
    {
        $typeClient->delete();
        return response()->json("Le type de terrain $typeClient->nom a été définitivement supprimé");
    }
}
