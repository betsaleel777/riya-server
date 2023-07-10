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
        Schema::table('appartements', function (Blueprint $table) {
            $table->foreignId('proprietaire_id')->constrained('proprietaires')->cascadeOnDelete();
            $table->foreignId('type_appartement_id')->nullable()->constrained('type_appartements')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appartements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('proprietaire_id');
            $table->dropConstrainedForeignId('type_appartement_id');
        });
    }
};
