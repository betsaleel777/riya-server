<?php

namespace App\Http\Controllers;

use App\Http\Requests\Operation\OperationRequest;
use App\Http\Resources\OperationResource;
use App\Models\Avance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $avances = Avance::get();
        return OperationResource::collection($avances);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OperationRequest $request): JsonResponse
    {
        $request->validated();
        $avance = Avance::make($request->all());
        $avance->save();
        return response()->json("L'avance a été crée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Avance $avance): JsonResource
    {
        return OperationResource::make($avance);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OperationRequest $request, Avance $avance): JsonResponse
    {
        $request->validated();
        $avance->update($request->all());
        return response()->json("L'avance a été modifié avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Avance $avance)
    {
        //
    }
}
