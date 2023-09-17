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
        Schema::create('bobot_preferensi_ahp', function (Blueprint $table) {
            $table->id();
            $table->string('kriteria_1');
            $table->string('kriteria_2');
            $table->integer('skala');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobot_preferensi_ahp');
    }
};
