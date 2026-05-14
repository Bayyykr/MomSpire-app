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
        Schema::create('kia_perkembangan_bayi_6_bulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');

            $table->boolean('berbalik')->nullable();
            $table->boolean('kepala_tegak_90')->nullable();
            $table->boolean('kepala_stabil')->nullable();
            $table->boolean('genggam_mainan')->nullable();
            $table->boolean('raih_benda')->nullable();
            $table->boolean('amati_tangan')->nullable();
            $table->boolean('luas_pandang')->nullable();
            $table->boolean('arah_mata')->nullable();
            $table->boolean('suara_gembira')->nullable();
            $table->boolean('senyum_mainan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_perkembangan_bayi_6_bulans');
    }
};
