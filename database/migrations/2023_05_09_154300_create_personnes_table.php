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
        Schema::create('personnes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('nom_complet', 190);
            $table->string('telephone', 15)->unique();
            $table->string('email', 100)->nullable()->unique();
            $table->string('cni')->unique();
            $table->string('lieu_naissance');
            $table->string('nationalite');
            $table->string('ville');
            $table->string('quartier', 60);
            $table->string('pays', 20);
            $table->string('fonctions');
            $table->string('animal', 20);
            $table->string('civilite', 3);
            $table->string('animal')->nullable();
            $table->date('date_naissance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnes');
    }
};
