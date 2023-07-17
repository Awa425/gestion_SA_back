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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->binary('photo');
            $table->mediumText('description');
            $table->date('event_date');
            $table->date('notfication_date');
            $table->time('event_time');
            $table->foreignId('user_id')
            ->references('id')
            ->on('users') ;
            $table->timestamps();
            $table->boolean('is_active')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
