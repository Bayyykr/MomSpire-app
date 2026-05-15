<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kia_pemeriksaan_trimester2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->cascadeOnDelete();
            
            // Skrining Preeklampsia
            $table->json('skrining_preeklampsia')->nullable(); // Store criteria results
            $table->string('kesimpulan_preeklampsia')->nullable();
            
            // Skrining Diabetes Melitus Gestasional
            $table->string('lab_gula_darah_puasa')->nullable();
            $table->string('lab_gula_darah_2jam')->nullable();
            $table->date('tgl_periksa_diabetes')->nullable();
            $table->string('nama_dokter_diabetes')->nullable();
            
            $table->timestamps();
        });

        Schema::create('kia_catatan_pelayanan_trimester2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->cascadeOnDelete();
            $table->date('tanggal_periksa')->nullable();
            $table->text('catatan')->nullable();
            $table->date('tanggal_kembali')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kia_catatan_pelayanan_trimester2');
        Schema::dropIfExists('kia_pemeriksaan_trimester2');
    }
};
