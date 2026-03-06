<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('depenses', function (Blueprint $table) {
            $table->date('date_depense')->nullable()->after('status');
        });
        DB::statement('UPDATE depenses SET date_depense = created_at WHERE date_depense IS NULL');
        DB::statement('ALTER TABLE depenses MODIFY COLUMN date_depense DATE NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('depenses', function (Blueprint $table) {
            $table->dropColumn('date_depense');
        });
    }
};
