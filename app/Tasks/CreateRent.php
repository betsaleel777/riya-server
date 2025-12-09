<?php

namespace App\Tasks;

use App\Models\Contrat;
use App\Models\Loyer;
use App\Repositories\DetteRepository;
use App\Repositories\LoyerRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateRent
{
    public function __invoke(): void
    {
        // Récupérer les repositories une seule fois
        $loyerRepository = app(LoyerRepository::class);
        $detteRepository = app(DetteRepository::class);

        $contrats = Contrat::with('operation.appartement', 'operation.avance')->rentProcessing()->get();
        $loyers = Loyer::currentMonth()->get();

        $contrats->each(function (Contrat $contrat) use ($loyers, $loyerRepository, $detteRepository) {
            if (!$contrat->operation || !$contrat->operation->avance) {
                Log::warning("Contrat {$contrat->id} sans avance, loyer non créé");
                return;
            }

            if ($contrat->encaissable() and !$loyers->contains('contrat_id', $contrat->id)) {
                DB::beginTransaction();
                try {
                    $loyer = $loyerRepository->create($contrat);
                    $contrat->setNotuptodate();
                    $detteRepository->storeForRent($loyer, $contrat);
                    DB::commit();
                    Log::info("Loyer créé pour le contrat {$contrat->id}");
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error("Erreur création loyer pour contrat {$contrat->id}: " . $e->getMessage());
                }
            }
        });
    }
}
