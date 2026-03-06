<?php

namespace Database\Seeders;

use App\Models\Visite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisiteDateInitialisation extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Visite::whereNull('visite_date')->update(['visite_date' => DB::raw('created_at')]);
    }
}
