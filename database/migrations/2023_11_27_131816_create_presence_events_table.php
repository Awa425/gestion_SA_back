<?php

use App\Models\Evenement;
use Illuminate\Support\Facades\Schema;
use App\Models\PromoReferentielApprenant;
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
            $table->foreignIdFor(PromoReferentielApprenant::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Evenement::class)->constrained()->cascadeOnDelete();
            $table->boolean('isPresent', false);
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
