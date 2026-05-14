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
        Schema::create('kia_perkembangan_bayi_9_bulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');

            $table->boolean('duduk_mandiri')->nullable();
            $table->boolean('tengkurap_dada')->nullable();
            $table->boolean('merangkak')->nullable();
            $table->boolean('pindah_benda')->nullable();
            $table->boolean('pungut_2_benda')->nullable();
            $table->boolean('pungut_kacang')->nullable();
            $table->boolean('bersuara_tanpa_arti')->nullable();
            $table->boolean('cari_mainan')->nullable();
            $table->boolean('tepuk_tangan')->nullable();
            $table->boolean('lempar_benda')->nullable();
            $table->boolean('makan_kue')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_perkembangan_bayi_9_bulans');
    }
};
