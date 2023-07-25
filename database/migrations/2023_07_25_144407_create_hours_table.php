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
        Schema::create('hours', function (Blueprint $table) {
            $table->date('date');
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->primary(['date','shop_id']);
            $table->string('opening_hour_morning',5);
            $table->string('closing_hour_morning',5);
            $table->string('opening_hour_afternoon',5);
            $table->string('closing_hour_afternoon',5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hours');
    }
};
