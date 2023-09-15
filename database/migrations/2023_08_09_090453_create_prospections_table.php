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
        Schema::create('prospections', function (Blueprint $table) {
            $table->id();
            $table->string('entreprise');
            $table->string('responsable');
            $table->string('fonction');
            $table->string('telephone');
            $table->string('email');
            $table->string('adresse');
            $table->mediumText('commentaire');
            $table->timestamp('date');
            $table->integer('nbre')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospections');
    }
};
