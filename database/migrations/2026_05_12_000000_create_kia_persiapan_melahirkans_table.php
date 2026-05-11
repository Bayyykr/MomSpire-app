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
        Schema::create('kia_persiapan_melahirkans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            
            // Kolom Kiri
            $table->boolean('tanya_tanggal_perkiraan')->default(false);
            $table->string('hpl_tanggal')->nullable();
            $table->string('hpl_bulan')->nullable();
            $table->string('hpl_tahun')->nullable();
            
            $table->boolean('minta_dampingi')->default(false);
            $table->boolean('siap_tabungan')->default(false);
            $table->boolean('kartu_jkn')->default(false);
            $table->boolean('tempat_melahirkan')->default(false);
            
            // Kolom Kanan
            $table->boolean('siap_ktp_kk')->default(false);
            $table->boolean('siap_pendonor')->default(false);
            $table->boolean('siap_kendaraan')->default(false);
            $table->boolean('sepakat_stiker_p4k')->default(false);
            
            $table->boolean('rencana_kb')->default(false);
            $table->string('metode_kb')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_persiapan_melahirkans');
    }
};
