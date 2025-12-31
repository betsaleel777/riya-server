<?php

namespace App\Http\Controllers;

use App\Enums\PayableStatus;
use App\Enums\ValidableEntityStatus;
use App\Http\Requests\Loyer\LoyerPatchRequest;
use App\Http\Requests\Loyer\LoyerPostRequest;
use App\Http\Resources\LoyerListResource;
use App\Http\Resources\LoyerResource;
use App\Http\Resources\LoyerValidationResource;
use App\Interfaces\LoyerRepositoryInterface;
use App\Interfaces\PaiementRepositoryInterface;
use App\Models\Dette;
use App\Models\Loyer;
use App\Models\Paiement;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class LoyerController extends Controller
{

    public function __construct(
        private PaiementRepositoryInterface $paiementRepository,
        private LoyerRepositoryInterface $loyerRepository,
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Loyer::class);
        $loyers = Loyer::withExists(['paiements as pending' => fn(Builder $query): Builder => $query->pending()])
            ->withSum('paiements as paid', 'montant')
            ->with('client:personnes.id,personnes.nom_complet', 'bien:appartements.id,appartements.nom')
            ->get();
        return LoyerListResource::collection($loyers);
    }

    public function getPaginate(): JsonResource
    {
        $this->authorize('viewAny', Loyer::class);
        $loyers = Loyer::withExists(['paiements as pending' => fn(Builder $query): Builder => $query->pending()])->latest()
            ->withSum('paiements as paid', 'montant')
            ->with('client:personnes.id,personnes.nom_complet', 'bien:appartements.id,appartements.nom')
            ->paginate(8);
        return LoyerListResource::collection($loyers->withPath('api/loyers/paginate'));
    }

    public function getSearch(Request $request): JsonResource
    {
        $this->authorize('viewAny', Loyer::class);
        $loyers = Loyer::withExists(['paiements as pending' => fn(Builder $query): Builder => $query->pending()])->latest()
            ->withSum('paiements as paid', 'montant')
            ->with('client:personnes.id,personnes.nom_complet', 'bien:appartements.id,appartements.nom')
            ->search($request->search)->paginate(8);
        return LoyerListResource::collection($loyers->withPath('api/loyers/search'));
    }

    public function getPending(): JsonResource
    {
        $this->authorize('viewPending', Loyer::class);
        $loyers = Loyer::select('id', 'code', 'montant', 'created_at', 'contrat_id')
            ->with('client:personnes.id,nom_complet', 'bien:appartements.id,nom', 'client.avatar:id,model_id,model_type,disk,file_name')
            ->pending()->get();
        return LoyerValidationResource::collection($loyers);
    }

    public function show(Loyer $loyer): JsonResource
    {
        $this->authorize('view', Loyer::class);
        $loyer->loadSum(['paiements as paid' => fn($query) => $query->validated()], 'montant')
            ->load(
                'bien:appartements.id,nom',
                'client:personnes.id,nom_complet,telephone,ville,quartier,email',
                'client.avatar:id,model_id,model_type,disk,file_name',
                'proprietaire:proprietaires.id,proprietaires.nom_complet,cni,proprietaires.email,proprietaires.telephone'
            )->load([
                'paiements' => fn(MorphMany $query): MorphMany => $query->withNameResponsible()
            ]);
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

    public function getLastPaid(Request $request): JsonResource|JsonResponse
    {
        $this->authorize('view', Loyer::class);
        $loyer = Loyer::where('contrat_id', $request->query('id'))->latest('id')->limit(1)->first();
        return $loyer ? LoyerResource::make($loyer) : response()->json(null);
    }

    public function avancer(LoyerPostRequest $request): JsonResponse
    {
        $this->authorize('create', Loyer::class);
        $request->validated();
        $this->loyerRepository->avancer($request->integer('contrat_id'), $request->periode, $this->paiementRepository);
        return response()->json("L'avance sur le loyer a été crée avec succès.");
    }

    public function destroy(Loyer $loyer): JsonResponse
    {
        $this->authorize('delete', Loyer::class);
        DB::beginTransaction();
        try {
            $loyer->loadMissing('contrat');
            $contrat = $loyer->contrat;
            $code = $loyer->code;
            $loyer->paiements()->delete();
            Dette::where('origine_type', Loyer::class)->where('origine_id', $loyer->id)->delete();
            $loyer->delete();

            if ($contrat) {
                $autresLoyers = Loyer::where('contrat_id', $contrat->id)->get();
                $autresLoyers->isEmpty() || $autresLoyers->every(fn($loyer) => $this->loyerRepository->checkUptodate($loyer))
                    ? $contrat->setUptodate()
                    : $contrat->setNotuptodate();
            }

            DB::commit();
            return response()->json("Le loyer {$code} a été supprimé avec succès.");
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur lors de la suppression du loyer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getStats(): JsonResponse
    {
        $this->authorize('viewStats', Loyer::class);

        $amounts = [
            'total' => (int)Loyer::currentMonth()->sum('montant'),
            'pending' => (int)Paiement::where('payable_type', Loyer::class)
                ->where('status', ValidableEntityStatus::WAIT)
                ->whereHas('payable', fn(Builder $query): Builder => $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]))
                ->sum('montant'),
            'paid' => (int)Paiement::where('payable_type', Loyer::class)
                ->where('status', ValidableEntityStatus::VALID)
                ->whereHas('payable', fn(Builder $query): Builder => $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]))
                ->sum('montant'),
        ];
        $total = array_sum($amounts);

        $unpaid = $amounts['total'] - $amounts['pending'] - $amounts['paid'];
        $stats = [
            'unpaid' => [
                'amount' => $unpaid,
                'percentage' => round(($unpaid / $total) * 100, 2),
                'title' => 'à recouvrer',
                'text' => 'texte à recouvrer'
            ],
            'pending' => [
                'amount' => $amounts['pending'],
                'percentage' => round(($amounts['pending'] / $total) * 100, 2),
                'title' => 'recouvré(s) en attente',
                'text' => 'texte recouvré en attente'
            ],
            'paid' => [
                'amount' => $amounts['paid'],
                'percentage' => round(($amounts['paid'] / $total) * 100, 2),
                'title' => 'recouvré(s)',
                'text' => 'texte recouvré'
            ]
        ];
        return response()->json($stats);
    }
}
