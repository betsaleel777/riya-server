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
        $this->authorize('viewAny', Frais::class);
        return OperationResource::collection(Frais::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OperationRequest $request): JsonResponse
    {
        $this->authorize('create', Frais::class);
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
        $this->authorize('view', Frais::class);
        return OperationResource::make($frai);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OperationRequest $request, Frais $frai): JsonResponse
    {
        $this->authorize('update', Frais::class);
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
