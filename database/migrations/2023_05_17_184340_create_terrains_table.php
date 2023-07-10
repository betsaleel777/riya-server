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
        Schema::create('terrains', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 10)->unique();
            $table->string('nom')->unique();
            $table->string('ville', 50);
            $table->string('pays', 50);
            $table->string('quartier', 50);
            $table->unsignedInteger('montant_location');
            $table->unsignedInteger('montant_investit');
            $table->unsignedInteger('cout_achat');
            $table->unsignedInteger('superficie');
            $table->boolean('attestation_villageoise')->default(false);
            $table->boolean('titre_foncier')->default(false);
            $table->boolean('document_cession')->default(false);
            $table->boolean('arreter_approbation')->default(false);
            $table->foreignId('proprietaire_id')->constrained('proprietaires')->cascadeOnDelete();
            $table->foreignId('type_terrain_id')->nullable()->constrained('type_terrains')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terrains');
    }
};
