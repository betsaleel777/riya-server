<?php

namespace App\Repositories;

use App\Enums\ContratOperationType;
use App\Enums\PayableStatus;
use App\Enums\ValidableEntityStatus;
use App\Events\ContratAchatCreated;
use App\Events\ContratBailCreated;
use App\Http\Requests\Contrat\ContratRequest;
use App\Interfaces\AchatRepositoryInterface;
use App\Interfaces\ContratRepositoryInterface;
use App\Models\Achat;
use App\Models\Appartement;
use App\Models\Contrat;
use App\Models\Loyer;
use App\Models\Paiement;
use App\Models\Terrain;
use App\Models\Visite;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ContratRepository implements ContratRepositoryInterface
{
    public function __construct(private AchatRepositoryInterface $achatRepository) {}

    public static function queryAchatAppartementByProprietaire(int $proprietaireId): Builder
    {
        return DB::table('proprietaires')
            ->join('appartements', 'proprietaires.id', '=', 'appartements.proprietaire_id')
            ->join('achats', function ($join) {
                $join->on('appartements.id', '=', 'achats.bien_id')
                    ->where('achats.bien_type', '=', Appartement::class);
            })
            ->join('personnes', 'achats.personne_id', '=', 'personnes.id')
            ->join('contrats', function ($join) {
                $join->on('achats.id', '=', 'contrats.operation_id')
                    ->where('contrats.operation_type', '=', Achat::class);
            })
            ->where('proprietaires.id', $proprietaireId)
            ->select(
                'contrats.id as id',
                'appartements.nom as bien',
                'achats.code as code',
                'personnes.nom_complet as client',
                'contrats.debut as debut',
                'contrats.fin as fin',
                'contrats.montant_location',
                'contrats.status as status',
                'contrats.operation_type',
                'contrats.etat',
                'contrats.created_at as created_at',
            );
    }

    public static function queryAchatTerrainByProprietaire(int $proprietaireId): Builder
    {
        return DB::table('proprietaires')
            ->join('terrains', 'proprietaires.id', '=', 'terrains.proprietaire_id')
            ->join('achats', function ($join) {
                $join->on('terrains.id', '=', 'achats.bien_id')
                    ->where('achats.bien_type', '=', Terrain::class);
            })
            ->join('personnes', 'achats.personne_id', '=', 'personnes.id')
            ->join('contrats', function ($join) {
                $join->on('achats.id', '=', 'contrats.operation_id')
                    ->where('contrats.operation_type', '=', Achat::class);
            })
            ->where('proprietaires.id', $proprietaireId)
            ->select(
                'contrats.id as id',
                'terrains.nom as bien',
                'achats.code as code',
                'personnes.nom_complet as client',
                'contrats.debut as debut',
                'contrats.fin as fin',
                'contrats.montant_location',
                'contrats.status as status',
                'contrats.operation_type',
                'contrats.etat',
                'contrats.created_at as created_at',
            );
    }

    public static function queryLocationByProprietaire(int $proprietaireId): Builder
    {
        return DB::table('proprietaires')
            ->join('appartements', 'proprietaires.id', '=', 'appartements.proprietaire_id')
            ->join('visites', 'appartements.id', '=', 'visites.appartement_id')
            ->join('contrats', function ($join) {
                $join->on('visites.id', '=', 'contrats.operation_id')
                    ->where('contrats.operation_type', '=', Visite::class);
            })
            ->join('personnes', 'visites.personne_id', '=', 'personnes.id')
            ->where('proprietaires.id', $proprietaireId)
            ->select(
                'contrats.id as id',
                'appartements.nom as bien',
                'visites.code as code',
                'personnes.nom_complet as client',
                'contrats.debut as debut',
                'contrats.fin as fin',
                'contrats.montant_location',
                'contrats.status as status',
                'contrats.operation_type',
                'contrats.etat',
                'contrats.created_at as created_at',
            );
    }

    /** @return Collection<int, array<string, mixed>> */
    public function getByProprietaire(int $proprietaireId): Collection
    {
        $rows = static::queryAchatAppartementByProprietaire($proprietaireId)
            ->unionAll(static::queryAchatTerrainByProprietaire($proprietaireId))
            ->unionAll(static::queryLocationByProprietaire($proprietaireId))
            ->orderBy('debut', 'desc')
            ->get();

        return $rows->map(function ($row) {
            return [
                'id' => $row->id,
                'bien' => $row->bien,
                'code' => $row->code,
                'client' => $row->client,
                'debut' => $row->debut instanceof \DateTimeInterface ? $row->debut->format('Y-m-d') : $row->debut,
                'fin' => $row->fin instanceof \DateTimeInterface ? $row->fin->format('Y-m-d') : $row->fin,
                'montant_location' => (int) $row->montant_location,
                'status' => $row->status,
                'operation_type' => class_basename($row->operation_type),
                'etat' => $row->etat,
                'created_at' => $row->created_at instanceof \DateTimeInterface ? $row->created_at->format('Y-m-d') : $row->created_at,
            ];
        })->values();
    }

    public function getByType(int $operationId, string $type): Visite | Achat
    {
        return match ($type) {
            ContratOperationType::VISITE->value => Visite::with('appartement')->find($operationId),
            ContratOperationType::ACHAT->value => Achat::with('bien')->find($operationId),
            default => throw new RuntimeException('Operation type not found'),
        };
    }

    public function store(ContratRequest $request): void
    {
        $contrat = Contrat::make($request->all());
        $operation = $this->getByType($request->operation_id, $request->operation_type);
        if ($operation instanceof Visite) {
            $contrat->montant_location = $operation->appartement->montant_location;
        }
        if ($operation instanceof Achat) {
            $contrat->cout_achat = $operation->bien->cout_achat;
        }
        $contrat->operation()->associate($operation)->save();
        if ($operation instanceof Visite) {
            ContratBailCreated::dispatch($contrat, $operation);
        }
        if ($operation instanceof Achat) {
            $paiement = Paiement::find($request->paiement);
            ContratAchatCreated::dispatch($paiement, $operation, $contrat);
        }
    }

    public function visiteUpdated(Contrat $contrat, int $montant): string
    {
        DB::beginTransaction();
        try {

            $loyers = Loyer::with(['dette', 'paiements'])->where('contrat_id', $contrat->id)->where('status', PayableStatus::UNPAID->value)->get();
            foreach ($loyers as $loyer) {
                if ($loyer->paiements->isNotEmpty()) {
                    DB::rollBack();
                    $contrat->loadMissing('operation');
                    return "Le contrat $contrat->code pour la visite {$contrat->operation->code} ne peut pas être modifié car il a des loyers avec des paiements partiels.";
                }
                $loyer->update(['montant' => $montant]);
                $loyer->dette->update(['montant' => $montant * $contrat->commission / 100]);
            }
            $contrat->update(['montant_location' => $montant]);
            $contrat->loadMissing('operation');

            DB::commit();
            return "Le contrat $contrat->code pour la visite {$contrat->operation->code} a été modifié avec succès.";
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function achatUpdated(Contrat $contrat, int $montant): string
    {
        DB::beginTransaction();
        try {
            $contrat->loadMissing('operation');
            /** @var Achat $achat */
            $achat = $contrat->operation;
            $achat->load(['paiements' => fn($query) => $query->where('status', ValidableEntityStatus::WAIT->value)]);
            if ($achat->paiements->isNotEmpty()) {
                DB::rollBack();
                return "Le contrat $contrat->code pour l'achat {$achat->code} ne peut pas être modifié car il a des paiements en attente de validation.";
            }

            $contrat->update(['cout_achat' => $montant]);
            $this->achatRepository->cascadeAchatUptodate($achat);

            DB::commit();

            return "Le contrat $contrat->code pour l'achat {$achat->code} a été modifié avec succès.";
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
