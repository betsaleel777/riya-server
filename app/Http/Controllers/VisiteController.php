<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contrat\ContratRequest;
use App\Http\Requests\Visite\VisiteRequest;
use App\Http\Resources\VisiteListResource;
use App\Http\Resources\VisiteResource;
use App\Models\Contrat;
use App\Models\Visite;
use App\Repositories\ContratRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class VisiteController extends Controller
{
    public function __construct(private ContratRepository $contratRepository)
    {
    }

    public function index(): JsonResource
    {
        $visites = Visite::with('personne', 'frais', 'caution', 'avance')->get();
        return VisiteListResource::collection($visites);
    }

    public function store(VisiteRequest $request): JsonResponse
    {
        $visite = Visite::make($request->validated());
        $visite->setExpiration();
        $visite->genererCode();
        $visite->save();
        return response()->json("La visite a été crée avec succès.");
    }


    public function show(Visite $visite): JsonResource
    {
        $oneVisite = Visite::with('appartement', 'personne', 'frais', 'caution', 'avance')->find($visite->id);
        return VisiteResource::make($oneVisite);
    }

    public function update(VisiteRequest $request, Visite $visite)
    {
        $visite->update($request->validated());
        return response()->json("La visite a été modifié avec succès.");
    }

    public function contratValidate(ContratRequest $request): JsonResponse
    {
        $request->validated();
        $visite = Visite::find($request->operation_id);
        $visite->setValide();
        $this->contratRepository->store($request);
        return response()->json("La visite $visite->code a été validée avec succès.");
    }

    public function directValidate(int $id): JsonResponse
    {
        $visite = Visite::find($id);
        $visite->setValide();
        return response()->json("La visite $visite->code a été validée avec succès.");
    }

    public function destroy(Visite $visite)
    {
        $visite->delete();
        return response()->json("La visite $visite->code a été supprimée avec succès.");
    }
}
