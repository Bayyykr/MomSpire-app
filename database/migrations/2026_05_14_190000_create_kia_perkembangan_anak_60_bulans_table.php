<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kia_perkembangan_anak_60_bulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->boolean('berdiri_1_kaki_6_detik')->nullable()->default(false);
            $table->boolean('lompat_1_kaki')->nullable()->default(false);
            $table->boolean('menari')->nullable()->default(false);
            $table->boolean('gambar_tanda_silang')->nullable()->default(false);
            $table->boolean('gambar_lingkaran')->nullable()->default(false);
            $table->boolean('gambar_orang_3_bagian')->nullable()->default(false);
            $table->boolean('kancing_baju_boneka')->nullable()->default(false);
            $table->boolean('sebut_nama_lengkap')->nullable()->default(false);
            $table->boolean('senang_sebut_kata_baru')->nullable()->default(false);
            $table->boolean('senang_bertanya')->nullable()->default(false);
            $table->boolean('jawab_pertanyaan_kata_benar')->nullable()->default(false);
            $table->boolean('bicara_mudah_dimengerti')->nullable()->default(false);
            $table->boolean('banding_ukuran_bentuk')->nullable()->default(false);
            $table->boolean('sebut_angka_hitung_jari')->nullable()->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kia_perkembangan_anak_60_bulans');
    }
};
