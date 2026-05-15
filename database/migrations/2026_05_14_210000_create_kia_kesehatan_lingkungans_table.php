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
        Schema::create('kia_kesehatan_lingkungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            
            // 1. Sarana Sanitasi
            $table->boolean('bab_sembarangan')->nullable();
            $table->boolean('bab_jamban_sendiri')->nullable();
            $table->boolean('penampung_tangki_septik')->nullable();
            $table->boolean('penampung_cubluk')->nullable();
            $table->boolean('penampung_drainase')->nullable();
            $table->boolean('kloset_leher_angsa')->nullable();

            // 2. Cuci Tangan Pakai Sabun
            $table->boolean('ctps_sarana')->nullable();
            $table->boolean('ctps_air_mengalir')->nullable();
            $table->boolean('ctps_sabun')->nullable();
            $table->boolean('ctps_waktu_sebelum_makan')->nullable();
            $table->boolean('ctps_waktu_sebelum_mengolah')->nullable();
            $table->boolean('ctps_waktu_sebelum_menyusui')->nullable();
            $table->boolean('ctps_waktu_setelah_bab')->nullable();

            // 3. Pengelolaan Makanan dan Air Minum
            $table->boolean('sumber_air_pipa')->nullable();
            $table->boolean('sumber_air_kran')->nullable();
            $table->boolean('sumber_air_sumur_terlindungi')->nullable();
            $table->boolean('sumber_air_mata_air_terlindungi')->nullable();
            $table->boolean('sumber_air_sungai')->nullable();
            $table->boolean('sumber_air_danau')->nullable();
            $table->boolean('sumber_air_hujan')->nullable();
            $table->boolean('sumber_air_waduk')->nullable();
            $table->boolean('sumber_air_kolam')->nullable();
            $table->boolean('sumber_air_irigasi')->nullable();
            $table->boolean('kelola_air_rebus')->nullable();
            $table->boolean('kelola_air_endap_saring')->nullable();
            $table->boolean('kelola_air_wadah_tertutup')->nullable();
            $table->boolean('kelola_makanan_tertutup')->nullable();
            $table->boolean('kelola_makanan_jauh_bahan_berbahaya')->nullable();
            $table->boolean('kelola_makanan_baik_benar')->nullable();

            // 4. Pengelolaan Sampah
            $table->boolean('sampah_tidak_berserakan')->nullable();
            $table->boolean('sampah_tempat_tertutup')->nullable();
            $table->boolean('sampah_dipilah')->nullable();
            $table->boolean('sampah_tidak_dibakar')->nullable();
            $table->boolean('sampah_tidak_dibuang_sembarangan')->nullable();

            // 5. Pengelolaan Limbah Cair
            $table->boolean('limbah_tidak_menggenang')->nullable();
            $table->boolean('limbah_saluran_tertutup')->nullable();
            $table->boolean('limbah_terhubung_resapan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_kesehatan_lingkungans');
    }
};
