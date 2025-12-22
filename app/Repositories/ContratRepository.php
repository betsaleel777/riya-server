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
use App\Models\Contrat;
use App\Models\Loyer;
use App\Models\Paiement;
use App\Models\Visite;
use Exception;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ContratRepository implements ContratRepositoryInterface
{
    public function __construct(private AchatRepositoryInterface $achatRepository) {}

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
