<?php

namespace App\Http\Controllers;

use App\Http\Requests\Loyer\LoyerPatchRequest;
use App\Http\Requests\Loyer\LoyerPostRequest;
use App\Http\Resources\LoyerListResource;
use App\Http\Resources\LoyerResource;
use App\Http\Resources\LoyerValidationResource;
use App\Interfaces\LoyerRepositoryInterface;
use App\Interfaces\PaiementRepositoryInterface;
use App\Models\Loyer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoyerController extends Controller
{

    public function __construct(private PaiementRepositoryInterface $paiementRepository, private LoyerRepositoryInterface $loyerRepository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Loyer::class);
        $loyers = Loyer::withExists(['paiements as pending' => fn(Builder $query): Builder => $query->pending()])
            ->withSum('paiements as paid', 'montant')->with('client:personnes.id,personnes.nom_complet', 'bien:appartements.id,appartements.nom')->get();
        return LoyerListResource::collection($loyers);
    }

    public function getPending(): JsonResource
    {
        $this->authorize('viewPending', Loyer::class);
        $loyers = Loyer::select('id', 'code', 'montant', 'created_at', 'contrat_id')->with('client:personnes.id,nom_complet', 'bien:appartements.id,nom', 'client.avatar:id,model_id,model_type,disk,file_name')->pending()->get();
        return LoyerValidationResource::collection($loyers);
    }

    /**
     * Display the specified resource.
     */
    public function show(Loyer $loyer): JsonResource
    {
        $this->authorize('view', Loyer::class);
        $loyer->loadSum(['paiements as paid' => fn($query) => $query->validated()], 'montant')->load('bien:appartements.id,nom', 'client:personnes.id,nom_complet,telephone,ville,quartier,email', 'client.avatar:id,model_id,model_type,disk,file_name',
            'proprietaire:proprietaires.id,proprietaires.nom_complet,cni,proprietaires.email,proprietaires.telephone')
            ->load(['paiements' => fn(MorphMany $query): MorphMany => $query->withNameResponsible()]);
        return LoyerResource::make($loyer);
    }

    public function valider(Loyer $loyer): JsonResponse
    {
        $this->authorize('valider', Loyer::class);
        $paiement = $this->loyerRepository->valider($loyer);
        return response()->json("Le paiement: $paiement->code du loyer: $loyer->code a été validé avec succès.");
    }

    public function encaisser(LoyerPatchRequest $request)
    {
        $this->authorize('encaisser', Loyer::class);
        $request->validated();
        $loyer = Loyer::find($request->query('id'));
        $this->paiementRepository->createPaiementLoyer($loyer, (int) $request->query('montant'));
        return response()->json("Le loyer $loyer->code a été encaissé avec succès.");
    }

    public function getLastPaid(Request $request): JsonResource
    {
        $this->authorize('view', Loyer::class);
        $loyer = Loyer::where('contrat_id', $request->query('id'))->latest('id')->limit(1)->first();
        return LoyerResource::make($loyer);
    }
    public function avancer(LoyerPostRequest $request): JsonResponse
    {
        $this->authorize('create', Loyer::class);
        $request->validated();
        $this->loyerRepository->avancer($request->integer('contrat_id'), $request->periode, $this->paiementRepository);
        return response()->json("L'avance sur le loyer a été crée avec succès.");
    }
}
