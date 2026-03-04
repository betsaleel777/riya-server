<?php

namespace App\Http\Controllers;

use App\Enums\ValidableEntityStatus;
use App\Http\Requests\Depense\DepensePostRequest;
use App\Http\Requests\Depense\DepensePutRequest;
use App\Http\Resources\DepenseListResource;
use App\Http\Resources\DepenseShowResource;
use App\Http\Resources\DepenseValidationResource;
use App\Models\Depense;
use App\Repositories\VisiteRepository;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class DepenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Depense::class);
        $depenses = Depense::select('id', 'titre', 'montant', 'type_depense_id', 'created_at', 'status')
            ->with(['type' => fn(BelongsTo $query) => $query->select('id', 'nom')])->get();
        return DepenseListResource::collection($depenses);
    }

    public function stats(): JsonResponse
    {
        $this->authorize('viewStats', Depense::class);
        $depenses = DB::table('depenses')->selectRaw('SUM(montant) as total')
            ->where('status', ValidableEntityStatus::VALID->value)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->first();
        $recettes = VisiteRepository::entreesDateFilter([now()->startOfMonth(), now()->endOfMonth()]);
        return response()->json([
            'depenses' => [
                'title' => 'Dépenses',
                'amount' => (int)$depenses->total,
            ],
            'recettes' => [
                'title' => 'Recettes',
                'amount' => $recettes,
            ],
            'solde' => [
                'title' => 'Solde',
                'amount' => $recettes - (int)$depenses->total,
            ],
        ]);
    }

    public function getPending(): JsonResource
    {
        $this->authorize('viewPending', Depense::class);
        $depenses = Depense::select('id', 'titre', 'montant', 'type_depense_id', 'created_at')
            ->with(['type' => fn(BelongsTo $query) => $query->select('id', 'nom')])->withResponsible()->pending()->get();
        return DepenseValidationResource::collection($depenses);
    }

    public function getPaginate(): JsonResource
    {
        $this->authorize('viewAny', Depense::class);
        $depenses = Depense::select('id', 'titre', 'montant', 'type_depense_id', 'created_at', 'status')->with('type:id,nom')->latest()
            ->paginate(8);
        return DepenseListResource::collection($depenses->withPath('api/depenses/paginate'));
    }

    public function getSearch(Request $request): JsonResource
    {
        $this->authorize('viewAny', Depense::class);
        $depenses = Depense::select('id', 'titre', 'montant', 'type_depense_id', 'created_at', 'status')
            ->with('type:id,nom')->latest()->search($request->search)->paginate(8);
        return DepenseListResource::collection($depenses->withPath('api/depenses/search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepensePostRequest $request): JsonResponse
    {
        $this->authorize('create', Depense::class);
        $depense = Depense::make($request->validated());
        $depense->save();
        return response()->json("La dépense $depense->titre a été crée avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Depense $depense): JsonResource
    {
        $this->authorize('view', Depense::class);
        return DepenseShowResource::make($depense->load('type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepensePutRequest $request, Depense $depense): JsonResponse
    {
        $this->authorize('update', Depense::class);
        $depense->update($request->validated());
        return response()->json("La dépense a été modifiée avec succès.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Depense $depense): JsonResponse
    {
        $this->authorize('delete', Depense::class);
        $depense->delete();
        return response()->json("La dépense $depense->titre a été supprimée avec succès.");
    }

    public function valider(Depense $depense): JsonResponse
    {
        $this->authorize('valider', Depense::class);
        $depense->setValide();
        return response()->json("La depense $depense->titre a été validée avec succès.");
    }
}
