<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contrat\ContratRequest;
use App\Http\Resources\ContratListResource;
use App\Http\Resources\ContratResource;
use App\Interfaces\ContratRepositoryInterface;
use App\Models\Achat;
use App\Models\Contrat;
use App\Models\Visite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ContratController extends Controller
{

    public function __construct(private ContratRepositoryInterface $contratRepository)
    {
    }

    public function index(): JsonResource
    {
        $this->authorize('viewAny', Contrat::class);
        return ContratListResource::collection(Contrat::with('operation.personne')->get());
    }

    public function getRentProcessing(): JsonResource
    {
        $this->authorize('view', Contrat::class);
        $contrats = Contrat::select('id', 'operation_id', 'operation_type')->with('operation:id,code')->rentProcessing()->get();
        return ContratResource::collection($contrats);
    }

    public function show(Contrat $contrat): JsonResource
    {
        $this->authorize('view', Contrat::class);
        $contrat->loadMorph('operation', [
            Achat::class => ['personne', 'bien'],
            Visite::class => ['personne', 'appartement', 'caution', 'avance'],
        ]);
        return ContratResource::make($contrat);
    }

    public function store(ContratRequest $request): JsonResponse
    {
        $this->authorize('create', Contrat::class);
        $request->validated();
        $this->contratRepository->store($request);
        $operation = $this->contratRepository->getByType($request->operation_id, $request->operation_type);
        return response()->json("Le contrat pour l'opération $operation->code a été crée avec succès.");
    }

    public function update(ContratRequest $request, Contrat $contrat): JsonResponse
    {
        $this->authorize('update', Contrat::class);
        $request->validated();
        $contrat->update($request->all());
        $visite = Visite::find($request->visite_id);
        return response()->json("Le contrat pour la visite $visite->code a modifié avec succès.");
    }

    public function abort(Contrat $contrat): JsonResponse
    {
        $this->authorize('resilier', Contrat::class);
        $contrat->setAborted();
        //run event when contrat aborted
        return response()->json("Le contrat a été résilié avec succès.");
    }

    public function contratValidate(ContratRequest $request): JsonResponse
    {
        $this->authorize('valider', Contrat::class);
        $request->validated();
        $operation = $this->contratRepository->getByType($request->operation_id, $request->operation_type);
        if ($operation instanceof Visite) {
            $operation->setValide();
        }
        $this->contratRepository->store($request);
        return response()->json("L'opération $operation->code a été validée avec succès.");
    }
}
