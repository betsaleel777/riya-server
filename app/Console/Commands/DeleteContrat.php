<?php

namespace App\Console\Commands;

use App\Enums\BienStatus;
use App\Models\Achat;
use App\Models\Contrat;
use App\Models\Dette;
use App\Models\Loyer;
use App\Models\Paiement;
use App\Models\Visite;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteContrat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contrat:delete {id : L\'ID du contrat à supprimer}
                            {--force : Forcer la suppression sans confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprimer un contrat et toutes ses dépendances (loyers, paiements, dettes)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $contratId = $this->argument('id');
        $force = $this->option('force');

        // Vérifier si le contrat existe
        $contrat = Contrat::with('operation')->find($contratId);

        if (!$contrat) {
            $this->error("❌ Le contrat avec l'ID {$contratId} n'existe pas.");
            return Command::FAILURE;
        }

        // Charger les relations pour afficher les informations
        $contrat->loadMorph('operation', [
            Achat::class => ['personne', 'bien'],
            Visite::class => ['personne', 'appartement'],
        ]);

        // Récupérer le bien concerné
        $bien = null;
        $bienInfo = 'N/A';

        if ($contrat->operation instanceof Visite) {
            $bien = $contrat->operation->appartement;
            $bienInfo = $bien->nom ?? $bien->reference ?? 'N/A';
        } elseif ($contrat->operation instanceof Achat) {
            $bien = $contrat->operation->bien;
            $bienInfo = $bien->nom ?? $bien->reference ?? 'N/A';
        }

        // Afficher les informations du contrat
        $this->info("Informations du contrat:");
        $this->table(
            ['Champ', 'Valeur'],
            [
                ['ID', $contrat->id],
                ['Date début', $contrat->debut->format('d/m/Y')],
                ['Date fin', $contrat->fin?->format('d/m/Y') ?? 'N/A'],
                ['État', $contrat->etat],
                ['Status', $contrat->status],
                ['Type opération', class_basename($contrat->operation_type)],
                ['Client', $contrat->operation->personne->nom_complet ?? 'N/A'],
                ['Bien', $bienInfo],
                ['Statut bien', $bien?->status ?? 'N/A'],
            ]
        );

        // Compter les enregistrements qui seront supprimés
        $loyersCount = Loyer::where('contrat_id', $contratId)->count();
        $paiementsCount = 0;
        $dettesCount = 0;

        // Compter selon le type de contrat
        $loyers = Loyer::where('contrat_id', $contratId)->get();

        if ($contrat->operation instanceof Visite) {
            // Contrat de location : compter paiements et dettes des loyers
            foreach ($loyers as $loyer) {
                $paiementsCount += Paiement::where('payable_type', Loyer::class)->where('payable_id', $loyer->id)->count();
                $dettesCount += Dette::where('origine_type', Loyer::class)->where('origine_id', $loyer->id)->count();
            }
        } elseif ($contrat->operation instanceof Achat) {
            // Contrat d'achat : compter paiements et dettes de l'achat
            $achat = $contrat->operation;
            $paiementsCount = Paiement::where('payable_type', Achat::class)->where('payable_id', $achat->id)->count();

            // Compter les dettes liées aux paiements de l'achat
            $paiementsAchat = Paiement::where('payable_type', Achat::class)
                ->where('payable_id', $achat->id)
                ->pluck('id');
            $dettesCount = Dette::where('origine_type', Paiement::class)->whereIn('origine_id', $paiementsAchat)->count();
        }

        $this->warn("\n  Éléments qui seront supprimés:");
        if ($contrat->operation instanceof Visite) {
            $this->line("   • {$loyersCount} loyer(s)");
            $this->line("   • {$paiementsCount} paiement(s) des loyers");
            $this->line("   • {$dettesCount} dette(s) liée(s) aux loyers");
        } elseif ($contrat->operation instanceof Achat) {
            $this->line("   • {$paiementsCount} paiement(s) de l'achat");
            $this->line("   • {$dettesCount} dette(s) liée(s) aux paiements");
        }

        if ($bien && $bien->status === BienStatus::BUSY->value) {
            $this->line("   • Le bien sera libéré (statut: {$bien->status} → " . BienStatus::FREE->value . ")");
        }

        // Demander confirmation si --force n'est pas utilisé
        if (!$force) {
            if (!$this->confirm("Êtes-vous sûr de vouloir supprimer ce contrat et toutes ses dépendances?", false)) {
                $this->info('Suppression annulée.');
                return Command::SUCCESS;
            }
        }
        DB::beginTransaction();

        try {
            $this->info("Suppression en cours...");

            if ($contrat->operation instanceof Visite) {
                // Contrat de location : supprimer dettes des loyers et paiements des loyers
                $dettesLoyersDeleted = 0;
                $paiementsDeleted = 0;

                foreach ($loyers as $loyer) {
                    $dettesLoyersDeleted += Dette::where('origine_type', Loyer::class)
                        ->where('origine_id', $loyer->id)
                        ->delete();
                    $paiementsDeleted += Paiement::where('payable_type', Loyer::class)
                        ->where('payable_id', $loyer->id)
                        ->delete();
                }

                $this->line("   ✓ {$dettesLoyersDeleted} dette(s) liée(s) aux loyers supprimée(s)");
                $this->line("   ✓ {$paiementsDeleted} paiement(s) des loyers supprimé(s)");

                $loyersDeleted = Loyer::where('contrat_id', $contratId)->delete();
                $this->line("   ✓ {$loyersDeleted} loyer(s) supprimé(s)");
            } elseif ($contrat->operation instanceof Achat) {
                // Contrat d'achat : supprimer dettes des paiements et paiements de l'achat
                $achat = $contrat->operation;
                $paiementsAchat = Paiement::where('payable_type', Achat::class)
                    ->where('payable_id', $achat->id)
                    ->pluck('id');

                $dettesDeleted = Dette::where('origine_type', Paiement::class)
                    ->whereIn('origine_id', $paiementsAchat)
                    ->delete();
                $this->line("   ✓ {$dettesDeleted} dette(s) liée(s) aux paiements supprimée(s)");

                $paiementsDeleted = Paiement::where('payable_type', Achat::class)
                    ->where('payable_id', $achat->id)
                    ->delete();
                $this->line("   ✓ {$paiementsDeleted} paiement(s) de l'achat supprimé(s)");
            }

            // Libérer le bien si il était occupé
            if ($bien && $bien->status === BienStatus::BUSY->value) {
                $bien->setFree();
                $this->line("   ✓ Bien libéré (statut: " . BienStatus::FREE->value . ")");
            }

            $contrat->delete();
            $this->line("   ✓ Contrat supprimé");
            DB::commit();

            $this->newLine();
            $this->info("✅ Le contrat {$contratId} et toutes ses dépendances ont été supprimés avec succès!");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            $this->error("\n❌ Erreur lors de la suppression: " . $e->getMessage());
            $this->error("La transaction a été annulée, aucune donnée n'a été supprimée.");

            return Command::FAILURE;
        }
    }
}
