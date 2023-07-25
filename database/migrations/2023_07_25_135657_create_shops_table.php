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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('city',60);
            $table->string('address',50)->nullable(); //se null lo shop è a domicilio
            $table->string('description', 255)->nullable();
            $table->string('logo')->nullable(); //se null c'è un immagine di default
            $table->string('opening_hour_morning',5);
            $table->string('closing_hour_morning',5);
            $table->string('opening_hour_afternoon',5);
            $table->string('closing_hour_afternoon',5);
            $table->boolean('pay'); //se = 0 il servizio è gratuito
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
