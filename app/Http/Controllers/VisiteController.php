<?php

namespace App\Http\Controllers;

use App\Http\Requests\Visite\VisiteRequest;
use App\Http\Resources\VisiteListResource;
use App\Http\Resources\VisiteResource;
use App\Http\Resources\VisiteValidationResource;
use App\Interfaces\ContratRepositoryInterface;
use App\Interfaces\VisiteRepositoryInterface;
use App\Models\Dette;
use App\Models\Loyer;
use App\Models\Paiement;
use App\Models\Visite;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class VisiteController extends Controller
{
    public function __construct(
        private ContratRepositoryInterface $contratRepository,
        private VisiteRepositoryInterface $visiteRepository
    ) {}

    public function index(): JsonResource
    {
        $this->authorize('viewAny', Visite::class);
        $visites = Visite::with('personne', 'frais', 'caution', 'avance')->get();
        return VisiteListResource::collection($visites);
    }

    public function getPending(): JsonResource
    {
        $this->authorize('viewPending', Visite::class);
        $visites = Visite::select('id', 'code', 'montant', 'created_at', 'frais_dossier', 'appartement_id', 'personne_id')
            ->with(['personne' => fn(BelongsTo $query) => $query->select('id', 'civilite', 'nom_complet')
                ->with('avatar:id,model_id,model_type,disk,file_name')])
            ->with(['frais' => fn(HasOne $query) => $query->select('id', 'mois', 'visite_id')])
            ->with(['caution' => fn(HasOne $query) => $query->select('id', 'mois', 'visite_id')])
            ->with(['avance' => fn(HasOne $query) => $query->select('id', 'mois', 'visite_id')])
            ->with(['appartement' =>
            fn(BelongsTo $query) => $query->select('id', 'montant_location', 'nom')])->pending()->get();
        return VisiteValidationResource::collection($visites);
    }

    public function store(VisiteRequest $request): JsonResponse
    {
        $this->authorize('create', Visite::class);
        $visite = Visite::make($request->validated());
        $visite->setExpiration();
        $visite->genererCode();
        $visite->save();
        return response()->json("La visite a été crée avec succès.");
    }

    public function show(Visite $visite): JsonResource
    {
        $this->authorize('view', Visite::class);
        $visite->load(
            'appartement',
            'personne',
            'frais',
            'caution',
            'avance',
            'audit:id,user_type,user_id,audits.auditable_id,audits.auditable_type',
            'audit.user:id,name',
            'audit.user.photo:id,model_id,model_type,disk,file_name'
        );
        return VisiteResource::make($visite);
    }

    public function update(VisiteRequest $request, Visite $visite)
    {
        $this->authorize('update', Visite::class);
        $visite->update($request->validated());
        return response()->json("La visite a été modifié avec succès.");
    }

    public function directValidate(int $id): JsonResponse
    {
        $this->authorize('valider', Visite::class);
        $visite = Visite::find($id);
        $visite->setValide();
        return response()->json("La visite $visite->code a été validée avec succès.");
    }

    public function destroy(Visite $visite)
    {
        $this->authorize('delete', Visite::class);

        DB::beginTransaction();
        try {
            $visite->loadMissing('contrat', 'appartement', 'dette');
            $code = $visite->code;
            if ($visite->contrat) {
                $contrat = $visite->contrat;
                $loyers = Loyer::where('contrat_id', $contrat->id)->get();
                foreach ($loyers as $loyer) {
                    Dette::where('origine_type', Loyer::class)->where('origine_id', $loyer->id)->delete();
                    Paiement::where('payable_type', Loyer::class)->where('payable_id', $loyer->id)->delete();
                }
                Loyer::where('contrat_id', $contrat->id)->delete();
                $contrat->delete();
            }
            optional($visite->dette)->delete();
            optional($visite->appartement)->setFree();
            $visite->delete();
            DB::commit();
            return response()->json("La visite $code a été supprimée avec succès.");
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur lors de la suppression de la visite',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function patchFraisDossier(Request $request, Visite $visite): JsonResponse
    {
        $this->authorize('update', Visite::class);
        $visite->load('personne');
        $visite->update($request->all());
        $this->visiteRepository->emitBailProcess($visite);
        $message = "Les frais de dossier ont été payés par le client " . $visite->personne->nom_complet;
        return response()->json($message);
    }
}
