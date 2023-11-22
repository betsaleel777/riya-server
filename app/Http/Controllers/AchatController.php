<?php

namespace App\Http\Controllers;

use App\Enums\PaiementType;
use App\Events\AchatCreated;
use App\Events\AchatDeleted;
use App\Http\Requests\Achat\AchatPostRequest;
use App\Http\Resources\AchatListResource;
use App\Http\Resources\AchatResource;
use App\Http\Resources\AchatValidationResource;
use App\Interfaces\AchatRepositoryInterface;
use App\Interfaces\BienRepositoryInterface;
use App\Interfaces\PaiementRepositoryInterface;
use App\Models\Achat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AchatController extends Controller
{
    public function __construct(
        private AchatRepositoryInterface $achatRepository,
        private BienRepositoryInterface $bienRepository,
        private PaiementRepositoryInterface $paiementRepository
    ) {
    }

    public function index(): JsonResource
    {
        $achats = Achat::withSum(['paiements as total' => fn($query) => $query->validated()], 'montant')
            ->with('bien:id,nom,cout_achat', 'personne:id,nom_complet')->get();
        return AchatListResource::collection($achats);
    }

    public function getPending(): JsonResource
    {
        $achats = Achat::with('bien:id,nom,cout_achat', 'personne:id,nom_complet', 'personne.piece:id,model_id,model_type,disk,file_name')->pending()->get();
        return AchatValidationResource::collection($achats);
    }

    /** dépendances:
     * modifie le status du bien à occupé
     * */
    public function store(AchatPostRequest $request): JsonResponse
    {
        $request->validated();
        $achat = Achat::make($request->all());
        $achat->genererCode();
        $bien = $this->bienRepository->getByType($request->bien_id, $request->bien_type);
        $achat->uptodate = $this->achatRepository->firstCheckUptodate($bien, $request->montant);
        $achat->bien()->associate($bien);
        $achat->save();
        $this->paiementRepository->createPaiement($achat->id, PaiementType::ACHAT->value, $request->montant);
        AchatCreated::dispatch($achat);
        return response()->json("L'achat $achat->code a été crée avec succès.");
    }

    public function show(Achat $achat): JsonResource
    {
        $achat->loadSum(['paiements as total' => fn($query) => $query->validated()], 'montant')
            ->load('bien:id,reference,nom,pays,ville,quartier,cout_achat,superficie', 'personne', 'paiements');
        return AchatResource::make($achat);
    }

    public function destroy(Achat $achat): JsonResponse
    {
        $achat->load('contrat');
        if ($achat->contrat->exists) {
            $message = "Suppression impossible, car l'achat $achat->code a un contrat en cours,
            pour supprimer cet achat vous devez résilier son contrat.";
        } else {
            $achat->delete();
            AchatDeleted::dispatch($achat);
            $message = "L'achat $achat->code a été supprimé avec succès.";
        }
        return response()->json($message);
    }

    public function valider(Achat $achat): JsonResponse
    {
        $paiement = $this->achatRepository->valider($achat);
        return response()->json("Le paiement $paiement->code pour le montant de $paiement->montant FCFA a été validé avec succès.");
    }
}
