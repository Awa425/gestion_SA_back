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
        Schema::create('promo__referentiel__apprenants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('promo_id');
            $table->unsignedBigInteger('referentiel_id');
            $table->unsignedBigInteger('apprenant_id');
            $table->foreign('promo_id')->references('id')->on('promos');
            $table->foreign('referentiel_id')->references('id')->on('referentiels');
            $table->foreign('apprenant_id')->references('id')->on('apprenants');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo__referentiel__apprenants');
    }
};
