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
        Schema::create('societes', function (Blueprint $table) {
            $table->id();
            $table->string('raison_sociale', 80);
            $table->string('slogan', 155);
            $table->string('email', 100);
            $table->string('boite_postale', 150);
            $table->string('forme_juridique', 50);
            $table->string('registre', 180);
            $table->string('contact', 15);
            $table->string('siege', 50);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('societes');
    }
};
