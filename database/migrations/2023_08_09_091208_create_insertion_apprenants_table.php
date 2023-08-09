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
        Schema::create('insertion_apprenants', function (Blueprint $table) {
            $table->id();
            $table->string('profil');
            $table->string('status');
            $table->string('type_contrat');
            $table->date('date_debut');
            $table->string('renumeration');
            $table->unsignedBigInteger('apprenant_id');
            $table->foreign('apprenant_id')->references('id')->on('apprenants');
            $table->unsignedBigInteger('prospection_id');
            $table->foreign('prospection_id')->references('id')->on('prospections');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insertion_apprenants');
    }
};
