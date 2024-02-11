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
        $loyers = Loyer::withExists(['paiements as pending' => fn(Builder $query): Builder => $query->pending()])
            ->withSum('paiements as paid', 'montant')->with('client:personnes.id,personnes.nom_complet', 'bien:appartements.id,appartements.nom')->get();
        return LoyerListResource::collection($loyers);
    }

    public function getPending(): JsonResource
    {
        $loyers = Loyer::select('id', 'code', 'montant', 'created_at', 'contrat_id')->with('client:personnes.id,nom_complet', 'bien:appartements.id,nom', 'client.avatar:id,model_id,model_type,disk,file_name')->pending()->get();
        return LoyerValidationResource::collection($loyers);
    }

    /**
     * Display the specified resource.
     */
    public function show(Loyer $loyer): JsonResource
    {
        $loyer->loadSum(['paiements as paid' => fn($query) => $query->validated()], 'montant')->load('bien:appartements.id,nom', 'client:personnes.id,nom_complet,telephone,ville,quartier,email', 'client.avatar:id,model_id,model_type,disk,file_name')
            ->load(['paiements' => fn(MorphMany $query): MorphMany => $query->withNameResponsible()]);
        return LoyerResource::make($loyer);
    }

    public function valider(Loyer $loyer): JsonResponse
    {
        $paiement = $this->loyerRepository->valider($loyer);
        return response()->json("Le paiement: $paiement->code du loyer: $loyer->code a été validé avec succès.");
    }

    public function encaisser(LoyerPatchRequest $request)
    {
        $request->validated();
        $loyer = Loyer::find($request->query('id'));
        $this->paiementRepository->createPaiementLoyer($loyer, (int) $request->query('montant'));
        return response()->json("Le loyer $loyer->code a été encaissé avec succès.");
    }

    public function getLastPaid(Request $request): JsonResource
    {
        $loyer = Loyer::where('contrat_id', $request->query('id'))->latest()->limit(1)->first();
        return LoyerResource::make($loyer);
    }
    public function avancer(LoyerPostRequest $request): JsonResponse
    {
        // créer les loyers pour chaque mois
        // encaisser ces loyers
        // créer aussi les dettes respectives
        $request->validated();
        $this->loyerRepository->avancer($request->integer('contrat_id'), $request->periode, $this->paiementRepository);
        return response()->json("L'avance sur le loyer a été crée avec succès.");
    }
}
