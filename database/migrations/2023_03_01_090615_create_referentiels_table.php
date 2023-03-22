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
        Schema::create('referentiels', function (Blueprint $table) {
            $table->id();
            $table->string('libelle', 255)->unique();
            $table->string('description');
            $table->boolean('is_active')->default(1);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('user_id')
            ->references('id')
            ->on('users') ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiels');
    }
};
