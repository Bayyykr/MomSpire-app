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
        Schema::create('kia_proses_melahirkans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            
            // Kolom Kiri
            $table->boolean('mulas_teratur')->default(false);
            $table->boolean('durasi_persalinan')->default(false);
            $table->boolean('hak_pendamping')->default(false);
            $table->boolean('hak_posisi')->default(false);
            
            // Kolom Kanan
            $table->boolean('ingin_bab')->default(false);
            $table->boolean('kurangi_sakit')->default(false);
            $table->boolean('inisiasi_menyusu_dini')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_proses_melahirkans');
    }
};
