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
        Schema::create('kia_pemantauan_ibu_nifas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->integer('hari_ke'); // Hari ke- 1 s/d 42
            
            // Section A (Halaman 16)
            $table->boolean('pemeriksaan_nifas')->default(false);
            $table->boolean('konsumsi_vitamin_a')->default(false);
            $table->boolean('konsumsi_ttd')->default(false);
            $table->boolean('pemenuhan_gizi')->default(false);
            $table->boolean('masalah_jiwa')->default(false);
            $table->boolean('demam')->default(false);
            $table->boolean('sakit_kepala')->default(false);
            $table->boolean('pandangan_kabur')->default(false);
            $table->boolean('nyeri_ulu_hati')->default(false);
            
            // Section B (Halaman 17)
            $table->boolean('jantung_berdebar')->default(false);
            $table->boolean('keluar_cairan_lahir')->default(false);
            $table->boolean('napas_pendek')->default(false);
            $table->boolean('payudara_bengkak')->default(false);
            $table->boolean('gangguan_bak')->default(false);
            $table->boolean('kelamin_bengkak')->default(false);
            $table->boolean('darah_nifas_berbau')->default(false);
            $table->boolean('pendarahan_hebat')->default(false);
            $table->boolean('keputihan')->default(false);
            
            // Kolom Kanan
            $table->string('paraf_kader_nakes')->nullable();
            
            $table->timestamps();
            
            // Indeks unik agar setiap hari hanya ada satu baris data per Ibu nifas
            $table->unique(['data_kia_id', 'hari_ke']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_pemantauan_ibu_nifas');
    }
};
