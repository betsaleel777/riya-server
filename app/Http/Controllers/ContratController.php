<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contrat\ContratRequest;
use App\Http\Resources\ContratListResource;
use App\Models\Contrat;
use App\Models\Visite;
use App\Repositories\ContratRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ContratController extends Controller
{

    public function __construct(private ContratRepository $contratRepository)
    {
    }

    public function index(): JsonResource
    {
        $contrats = Contrat::with('operation')->get();
        return ContratListResource::collection($contrats);
    }

    public function store(ContratRequest $request): JsonResponse
    {
        $request->validated();
        $this->contratRepository->store($request);
        $visite = Visite::find($request->visite_id);
        return response()->json("Le contrat pour la visite $visite->code a été crée avec succès.");
    }

    public function update(ContratRequest $request, Contrat $contrat): JsonResponse
    {
        $request->validated();
        $contrat->update($request->all());
        $visite = Visite::find($request->visite_id);
        return response()->json("Le contrat pour la visite $visite->code a modifié avec succès.");
    }

    public function abort(Contrat $contrat): JsonResponse
    {
        $contrat->setAborted();
        //run event when contrat aborted
        return response()->json("Le contrat a été résilié avec succès.");
    }
}
