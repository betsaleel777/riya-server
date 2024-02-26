<?php

namespace App\Http\Controllers;

use App\Http\Requests\Personne\StoreTypeRequest;
use App\Http\Requests\Personne\UpdateTypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\TypeClient;
use Illuminate\Http\Resources\Json\JsonResource;

class TypeClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', TypeClient::class);
        return TypeResource::collection(TypeClient::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeRequest $request)
    {
        $this->authorize('create', TypeClient::class);
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
        $this->authorize('view', TypeClient::class);
        return TypeResource::make($typeClient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeRequest $request, TypeClient $typeClient)
    {
        $this->authorize('update', TypeClient::class);
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
        $this->authorize('delete', TypeClient::class);
        $typeClient->delete();
        return response()->json("Le type de terrain $typeClient->nom a été définitivement supprimé");
    }
}
