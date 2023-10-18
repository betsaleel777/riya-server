<?php

namespace App\Http\Controllers;

use App\Http\Requests\Operation\OperationRequest;
use App\Http\Resources\OperationResource;
use App\Interfaces\VisiteRepositoryInterface;
use App\Models\Caution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CautionController extends Controller
{
    public function __construct(private VisiteRepositoryInterface $visiteRepository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $cautions = Caution::get();
        return OperationResource::collection($cautions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OperationRequest $request)
    {
        $request->validated();
        $caution = Caution::make($request->all());
        $caution->save();
        $visite = $this->visiteRepository->emitBailProcess($request->visite_id);
        return response()->json("La caution pour la location $visite->code a été enregistrée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Caution $caution): JsonResource
    {
        return OperationResource::make($caution);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OperationRequest $request, Caution $caution): JsonResponse
    {
        $request->validated();
        $caution->update($request->all());
        return response()->json("La caution a été modifié avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caution $caution)
    {
        //
    }
}
