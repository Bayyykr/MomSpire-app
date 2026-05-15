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
        Schema::dropIfExists('kia_pemeriksaan_usgs');

        Schema::create('kia_pemeriksaan_trimester1', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->cascadeOnDelete();
            $table->string('gambar_usg')->nullable();
            
            // Pemeriksaan Laboratorium
            $table->date('tgl_periksa_lab')->nullable();
            $table->string('lab_hemoglobin')->nullable();
            $table->string('lab_gol_darah')->nullable();
            $table->string('lab_gula_darah')->nullable();
            $table->enum('lab_tripel_h', ['Reaktif', 'Non reaktif'])->nullable();
            $table->enum('lab_tripel_s', ['Reaktif', 'Non reaktif'])->nullable();
            $table->enum('lab_tripel_hep_b', ['Reaktif', 'Non reaktif'])->nullable();
            
            // Skrining Kesehatan Jiwa
            $table->date('tgl_skrining_jiwa')->nullable();
            $table->enum('skrining_jiwa', ['Ya', 'Tidak'])->nullable();
            $table->enum('tindak_lanjut_jiwa', ['Edukasi', 'Konseling'])->nullable();
            $table->enum('rujukan_jiwa', ['Ya', 'Tidak'])->nullable();
            
            $table->string('kesimpulan')->nullable();
            $table->string('rekomendasi')->nullable();
            
            $table->timestamps();
        });

        Schema::create('kia_catatan_pelayanan_trimester1', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->cascadeOnDelete();
            $table->date('tanggal_periksa')->nullable();
            $table->text('catatan')->nullable(); // Keluhan, Pemeriksaan, Tindakan dan Saran
            $table->date('tanggal_kembali')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_catatan_pelayanan_trimester1');
        Schema::dropIfExists('kia_pemeriksaan_trimester1');
    }
};
