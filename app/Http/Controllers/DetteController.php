<?php

namespace App\Http\Controllers;

use App\Enums\PayableStatus;
use App\Http\Resources\DetteListResource;
use App\Http\Resources\DetteResource;
use App\Http\Resources\DetteValidationResource;
use App\Models\Achat;
use App\Models\Dette;
use App\Models\Loyer;
use App\Models\Paiement;
use App\Models\Visite;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class DetteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Dette::class);
        return DetteListResource::collection(Dette::with('origine')->get());
    }

    public function getPaginate(): JsonResource
    {
        $this->authorize('viewAny', Dette::class);
        $dettes = Dette::with('origine')->paginate(8);
        return DetteListResource::collection($dettes->withPath('api/dettes/paginate'));
    }

    public function getSearch(Request $request): JsonResource
    {
        $this->authorize('viewAny', Dette::class);
        $dettes = Dette::with('origine')->search($request->search)->paginate(8);
        return DetteListResource::collection($dettes->withPath('api/dettes/search'));
    }


    public function getPending(): JsonResource
    {
        $this->authorize('viewPending', Dette::class);
        $dettes = Dette::with('origine')->withResponsible()->pending()->get();
        return DetteValidationResource::collection($dettes);
    }

    /**
     * Display the specified resource.
     */
    public function show(Dette $dette): JsonResource
    {
        $this->authorize('view', Dette::class);
        $dette->loadMorph('origine', [
            Visite::class => ['contrat:id,debut,commission,created_at,operation_id,operation_type'],
            Paiement::class => ['payable.contrat:id,debut,commission,created_at,operation_id,operation_type'],
            Loyer::class => ['contrat:id,debut,commission,created_at,operation_id,operation_type'],
        ]);
        return DetteResource::make($dette);
    }

    public function rembourser(Dette $dette): JsonResponse
    {
        $this->authorize('update', Dette::class);
        $dette->setPending();
        return response()->json("Le rembourssement de la dette $dette->code a bien été enregistré.");
    }

    public function valider(Dette $dette): JsonResponse
    {
        $this->authorize('valider', Dette::class);
        $dette->setPaid();
        return response()->json("Le rembourssement de la dette $dette->code a bien été validé.");
    }

    /**
     * Obtenir les statistiques des dettes par type (visite, loyer, achat)
     * avec leurs remboursements respectifs et données pour graphiques
     */
    public function getStats(): JsonResponse
    {
        $this->authorize('viewStats', Dette::class);

        // Dettes sur visite
        $origine = [Visite::class, Loyer::class];
        $resultats = [];
        foreach ($origine as $type) {
            $stats = Dette::where('origine_type', $type)
                ->selectRaw('
                    SUM(montant) as total,
                    SUM(CASE WHEN status = ? THEN montant ELSE 0 END) as rembourse,
                    SUM(CASE WHEN status = ? THEN montant ELSE 0 END) as en_attente
                ', [
                    PayableStatus::PAID->value,
                    PayableStatus::PENDING->value,
                ])
                ->first();
            $total = (int) $stats->total;
            $rembourse = (int) $stats->rembourse;
            $enAttente = (int) $stats->en_attente;
            $impaye = $total - $rembourse - $enAttente;
            $key = class_basename($type);
            $resultats[$key] = [
                'paid' => [
                    'amount' => $rembourse,
                    'percentage' => $total > 0 ? round(($rembourse / $total) * 100, 2) : 0,
                ],
                'pending' => [
                    'amount' => $enAttente,
                    'percentage' => $total > 0 ? round(($enAttente / $total) * 100, 2) : 0,
                ],
                'unpaid' => [
                    'amount' => $impaye,
                    'percentage' => $total > 0 ? round(($impaye / $total) * 100, 2) : 0,
                ],
            ];
        }

        $dettesAchatQuery = Dette::whereHasMorph('origine', [Paiement::class], function ($query) {
            $query->where('payable_type', Achat::class);
        });
        $total = (int) $dettesAchatQuery->sum('montant');
        $rembourseAchat = (int) $dettesAchatQuery->paid()->sum('montant');
        $enAttenteAchat = (int) $dettesAchatQuery->pending()->sum('montant');
        $impayeAchat = $total - $rembourseAchat - $enAttenteAchat;
        $resultats['Achat'] = [
            'paid' => [
                'amount' => $rembourseAchat,
                'percentage' => $total > 0 ? round(($rembourseAchat / $total) * 100, 2) : 0,
            ],
            'pending' => [
                'amount' => $enAttenteAchat,
                'percentage' => $total > 0 ? round(($enAttenteAchat / $total) * 100, 2) : 0,
            ],
            'unpaid' => [
                'amount' => $impayeAchat,
                'percentage' => $total > 0 ? round(($impayeAchat / $total) * 100, 2) : 0,
            ],
        ];
        return response()->json($resultats);
    }
}
