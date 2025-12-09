<?php

namespace Database\Seeders;

use App\Enums\ContratState;
use App\Models\Contrat;
use Illuminate\Database\Seeder;

class ContratBusyHome extends Seeder
{
    public function run(): void
    {
        Contrat::with('operation.appartement')->where('etat', ContratState::USING)->get()->each(function (Contrat $contrat) {
            $appartement = $contrat->operation->appartement;
            if ($appartement) {
                $appartement->setBusy();
                $this->command->info("Appartement {$appartement->nom} est maintenant occupé");
            }
        });
    }
}
