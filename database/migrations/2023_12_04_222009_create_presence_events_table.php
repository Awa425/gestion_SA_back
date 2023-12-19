<?php

use App\Models\Apprenant;
use App\Models\Evenement;
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
        Schema::create('presence_events', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Apprenant::class)->constrained()->cascadeOnDelete()->nullable();
            $table->foreignIdFor(Evenement::class)->constrained()->cascadeOnDelete();
            $table->string("nom")->nullable();
            $table->string("prenom")->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('cni', 255)->nullable();
            $table->enum('genre',['Feminin','Masculin'])->nullable();
            $table->boolean('is_present')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presence_events');
    }
};
