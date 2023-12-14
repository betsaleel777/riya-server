<?php

namespace App\Http\Controllers;

use App\Http\Requests\Visite\VisiteRequest;
use App\Http\Resources\VisiteListResource;
use App\Http\Resources\VisiteResource;
use App\Http\Resources\VisiteValidationResource;
use App\Interfaces\ContratRepositoryInterface;
use App\Interfaces\VisiteRepositoryInterface;
use App\Models\Visite;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisiteController extends Controller
{
    public function __construct(private ContratRepositoryInterface $contratRepository, private VisiteRepositoryInterface $visiteRepository)
    {
    }

    public function index(): JsonResource
    {
        $visites = Visite::with('personne', 'frais', 'caution', 'avance')->get();
        return VisiteListResource::collection($visites);
    }

    public function getPending(): JsonResource
    {
        $visites = Visite::select('id', 'code', 'montant', 'created_at', 'frais_dossier', 'appartement_id', 'personne_id')
            ->with(['personne' => fn(BelongsTo $query) => $query->select('id', 'civilite', 'nom_complet')])
            ->with(['frais' => fn(HasOne $query) => $query->select('id', 'mois', 'visite_id')])
            ->with(['caution' => fn(HasOne $query) => $query->select('id', 'mois', 'visite_id')])
            ->with(['avance' => fn(HasOne $query) => $query->select('id', 'mois', 'visite_id')])
            ->with(['appartement' => fn(BelongsTo $query) => $query->select('id', 'montant_location', 'nom')])->pending()->get();
        return VisiteValidationResource::collection($visites);
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
        $visite->load('appartement', 'personne', 'frais', 'caution', 'avance', 'audit:id,user_type,user_id,audits.auditable_id,audits.auditable_type', 'audit.user:id,name', 'audit.user.photo:id,model_id,model_type,disk,file_name');
        return VisiteResource::make($visite);
    }

    public function update(VisiteRequest $request, Visite $visite)
    {
        $visite->update($request->validated());
        return response()->json("La visite a été modifié avec succès.");
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

    public function patchFraisDossier(Request $request, Visite $visite): JsonResponse
    {
        $visite->load('personne');
        $visite->update($request->all());
        $this->visiteRepository->emitBailProcess($visite);
        $message = "Les frais de dossier ont été payés par le client " . $visite->personne->nom_complet;
        return response()->json($message);
    }
}
