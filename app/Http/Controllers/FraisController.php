<?php

namespace App\Http\Controllers;

use App\Http\Requests\Operation\OperationRequest;
use App\Http\Resources\OperationResource;
use App\Models\Frais;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class FraisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $frais = Frais::get();
        return OperationResource::collection($frais);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OperationRequest $request): JsonResponse
    {
        $request->validated();
        $frais = Frais::make($request->all());
        $frais->save();
        return response()->json("Les frais ont été enregistré");
    }

    /**
     * Display the specified resource.
     */
    public function show(Frais $frais): JsonResource
    {
        return OperationResource::make($frais);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OperationRequest $request, Frais $frais): JsonResponse
    {
        $request->validated();
        $frais->update($request->all());
        return response()->json("Les frais ont bien été modifié");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
