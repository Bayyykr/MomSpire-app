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
        Schema::create('kia_evaluasi_kesehatan_ibus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            
            $table->string('nama_dokter')->nullable();
            $table->date('tanggal_periksa')->nullable();
            $table->string('fasilitas_kesehatan')->nullable();
            
            // Kondisi Kesehatan Ibu
            $table->float('tb')->nullable();
            $table->float('bb')->nullable();
            $table->string('imt')->nullable(); // Kurus, Normal, Gemuk, Obesitas
            $table->float('lila')->nullable();
            
            // Status Imunisasi TD (Booleans for Perlindungan checkmarks)
            $table->boolean('imunisasi_tt_1')->default(false);
            $table->boolean('imunisasi_tt_2')->default(false);
            $table->boolean('imunisasi_tt_3')->default(false);
            $table->boolean('imunisasi_tt_4')->default(false);
            $table->boolean('imunisasi_tt_5')->default(false);
            
            // JSON Arrays for Checkboxes
            $table->json('riwayat_kesehatan_ibu')->nullable();
            $table->string('riwayat_kesehatan_ibu_lainnya')->nullable();
            
            $table->json('riwayat_perilaku')->nullable();
            $table->string('riwayat_perilaku_lainnya')->nullable();
            
            $table->json('riwayat_penyakit_keluarga')->nullable();
            $table->string('riwayat_penyakit_keluarga_lainnya')->nullable();
            
            // Pemeriksaan Khusus
            $table->string('inspeksi_porsio')->nullable();
            $table->string('inspeksi_uretra')->nullable();
            $table->string('inspeksi_vagina')->nullable();
            $table->string('inspeksi_vulva')->nullable();
            $table->string('inspeksi_fluksus')->nullable();
            $table->string('inspeksi_fluor')->nullable();
            
            // Riwayat Kehamilan dan Proses Melahirkan (JSON array of max 3 objects)
            $table->json('riwayat_kehamilan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_evaluasi_kesehatan_ibus');
    }
};
