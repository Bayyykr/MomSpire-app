<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kia_pelayanan_kesehatan_ibus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->integer('kunjungan_ke');
            $table->date('tanggal_periksa')->nullable();
            $table->string('tempat_periksa')->nullable();
            $table->string('berat_badan')->nullable();
            $table->string('tinggi_badan')->nullable();
            $table->string('lingkar_lengan_atas')->nullable();
            $table->string('tekanan_darah')->nullable();
            $table->string('tinggi_rahim')->nullable();
            $table->string('letak_janin')->nullable();
            $table->string('denyut_jantung_bayi')->nullable();
            $table->string('status_imunisasi_tt')->nullable();
            $table->string('konseling')->nullable();
            $table->string('skrining_dokter')->nullable();
            $table->string('tablet_tambah_darah')->nullable();
            $table->string('tes_lab_hb')->nullable();
            $table->string('tes_golongan_darah')->nullable();
            $table->string('tes_lab_protein_urine')->nullable();
            $table->string('tes_lab_gula_darah')->nullable();
            $table->string('usg')->nullable();
            $table->string('tripel_eliminasi')->nullable();
            $table->string('tata_laksana_kasus')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kia_pelayanan_kesehatan_ibus');
    }
};
