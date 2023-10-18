<?php

namespace App\Http\Controllers;

use App\Http\Requests\Operation\OperationRequest;
use App\Http\Resources\OperationResource;
use App\Interfaces\VisiteRepositoryInterface;
use App\Models\Frais;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class FraisController extends Controller
{

    public function __construct(private VisiteRepositoryInterface $visiteRepository)
    {
    }
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
        $visite = $this->visiteRepository->emitBailProcess($request->visite_id);
        return response()->json("Les frais pour la location $visite->code ont été enregistré");
    }

    /**
     * Display the specified resource.
     */
    public function show(Frais $frai): JsonResource
    {
        return OperationResource::make($frai);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OperationRequest $request, Frais $frai): JsonResponse
    {
        $request->validated();
        $frai->update($request->all());
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
