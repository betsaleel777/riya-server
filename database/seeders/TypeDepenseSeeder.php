<?php

namespace Database\Seeders;

use App\Models\TypeDepense;
use Illuminate\Database\Seeder;

class TypeDepenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = collect([
            ['nom' => 'salaire'],
            ['nom' => 'carburant'],
            ['nom' => 'réparation moto'],
            ['nom' => 'internet'],
            ['nom' => 'rechargement crédit téléphonique'],
            ['nom' => 'achatfournitures de bureau'],
            ['nom' => 'cachet'],
            ['nom' => 'reçu de paiement'],
            ['nom' => 'téléphone'],
            ['nom' => 'flyers'],
            ['nom' => 'sponsorisation de page'],
            ['nom' => 'impôts'],
            ['nom' => 'CNPS'],
            ['nom' => 'ITS'],
            ['nom' => 'Tee-shirts'],
            ['nom' => 'Casquette'],
            ['nom' => 'Kakémono'],
            ['nom' => 'Pancarte'],
            ['nom' => 'affiche'],
            ['nom' => 'bache'],
            ['nom' => 'oriflamme'],
            ['nom' => 'branding'],
            ['nom' => 'meubles'],
            ['nom' => 'assurances'],
            ['nom' => 'chemise'],
            ['nom' => 'logiciel'],
        ]);
        $types->each(fn($type) => TypeDepense::create($type));
    }
}
