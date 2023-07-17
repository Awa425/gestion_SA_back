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
        Schema::create('evenement_referentiels', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('evenement_id')
            ->references('id')
            ->on('evenements') ;
            $table->foreignId('promo_referentiel_id')->constrained('promo_referentiels');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenement_referentiels');
    }
};
