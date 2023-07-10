<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('visites', function (Blueprint $table) {
            $table->foreignId('appartement_id')->constrained('appartements')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visites', function (Blueprint $table) {
            $table->dropConstrainedForeignId('appartement_id');
        });
    }
};
