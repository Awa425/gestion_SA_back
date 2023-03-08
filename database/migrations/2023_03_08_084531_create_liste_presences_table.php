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
        Schema::create('liste_presences', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_heure_arriver')->nullable();
            $table->unsignedBigInteger('apprenant_id');
            $table->timestamps();
            $table->foreign('apprenant_id')->references('id')->on('apprenants');  
       });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liste_presences');
    }
};
