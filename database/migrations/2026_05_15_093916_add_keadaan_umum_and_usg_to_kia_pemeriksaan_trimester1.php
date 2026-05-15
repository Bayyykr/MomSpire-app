<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kia_pemeriksaan_trimester1', function (Blueprint $table) {
            // Keadaan Umum
            $table->string('konjungtiva')->nullable();
            $table->string('sklera')->nullable();
            $table->string('kulit')->nullable();
            $table->string('leher')->nullable();
            $table->string('gigi_mulut')->nullable();
            $table->string('tht')->nullable();
            $table->string('dada_jantung')->nullable();
            $table->string('dada_paru')->nullable();
            $table->string('perut')->nullable();
            $table->string('tungkai')->nullable();

            // USG Trimester 1
            $table->date('hpht')->nullable();
            $table->string('keteraturan_haid')->nullable();
            $table->integer('usia_kehamilan_hpht')->nullable();
            $table->date('hpl_hpht')->nullable();
            $table->integer('usia_kehamilan_usg')->nullable();
            $table->date('hpl_usg')->nullable();
            $table->string('jumlah_gs')->nullable();
            $table->float('diameter_gs_cm')->nullable();
            $table->integer('diameter_gs_minggu')->nullable();
            $table->integer('diameter_gs_hari')->nullable();
            $table->string('jumlah_bayi')->nullable();
            $table->float('crl_cm')->nullable();
            $table->integer('crl_minggu')->nullable();
            $table->integer('crl_hari')->nullable();
            $table->string('letak_produk_kehamilan')->nullable();
            $table->string('pulsasi_jantung')->nullable();
            $table->string('kecurigaan_temuan_abnormal')->nullable();
            $table->string('kecurigaan_temuan_abnormal_sebutkan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('kia_pemeriksaan_trimester1', function (Blueprint $table) {
            $table->dropColumn([
                'konjungtiva', 'sklera', 'kulit', 'leher', 'gigi_mulut', 'tht',
                'dada_jantung', 'dada_paru', 'perut', 'tungkai',
                'hpht', 'keteraturan_haid', 'usia_kehamilan_hpht', 'hpl_hpht',
                'usia_kehamilan_usg', 'hpl_usg', 'jumlah_gs', 'diameter_gs_cm',
                'diameter_gs_minggu', 'diameter_gs_hari', 'jumlah_bayi', 'crl_cm',
                'crl_minggu', 'crl_hari', 'letak_produk_kehamilan', 'pulsasi_jantung',
                'kecurigaan_temuan_abnormal', 'kecurigaan_temuan_abnormal_sebutkan'
            ]);
        });
    }
};
