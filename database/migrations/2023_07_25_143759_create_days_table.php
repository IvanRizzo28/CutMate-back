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
        //questa tabella ha priorità sulle altre
        Schema::create('days', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->boolean('is_close')->default(true); // se = 1 allora quel giorno è chiuso
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('days');
    }
};
