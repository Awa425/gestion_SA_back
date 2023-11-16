<?php

use App\Models\PromoReferentiel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emploie_du_temps', function (Blueprint $table) {
            $table->id();
            $table->string('nom_cours');
            $table->date('date_cours');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->foreignId('prof_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(PromoReferentiel::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emploie_du_temps');
    }
};
