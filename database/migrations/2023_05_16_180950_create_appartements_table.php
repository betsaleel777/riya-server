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
        Schema::create('appartements', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 10)->unique();
            $table->string('nom')->unique();
            $table->string('ville', 50);
            $table->string('pays', 50);
            $table->string('quartier', 50);
            $table->unsignedInteger('superficie');
            $table->unsignedInteger('montant_location');
            $table->unsignedInteger('montant_investit');
            $table->unsignedInteger('cout_achat');
            $table->longText('observation')->nullable();
            $table->boolean('attestation_villageoise')->default(false);
            $table->boolean('titre_foncier')->default(false);
            $table->boolean('document_cession')->default(false);
            $table->boolean('arreter_approbation')->default(false);
            $table->boolean('cours_commune')->default(false);
            $table->boolean('placard')->default(false);
            $table->boolean('etage')->default(false);
            $table->boolean('toilette')->default(false);
            $table->boolean('cuisine')->default(false);
            $table->boolean('garage')->default(false);
            $table->boolean('parking')->default(false);
            $table->boolean('cie')->default(false);
            $table->boolean('sodeci')->default(false);
            $table->boolean('cloture')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appartements');
    }
};
