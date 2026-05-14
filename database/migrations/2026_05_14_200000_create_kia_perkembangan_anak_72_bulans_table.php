<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kia_perkembangan_anak_72_bulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->boolean('berjalan_lurus')->nullable()->default(false);
            $table->boolean('berdiri_1_kaki_11_detik')->nullable()->default(false);
            $table->boolean('gambar_6_bagian_orang_lengkap')->nullable()->default(false);
            $table->boolean('tangkap_bola_kecil')->nullable()->default(false);
            $table->boolean('gambar_segi_empat')->nullable()->default(false);
            $table->boolean('mengerti_lawan_kata')->nullable()->default(false);
            $table->boolean('mengerti_pembicaraan_7_kata')->nullable()->default(false);
            $table->boolean('jawab_bahan_guna_benda')->nullable()->default(false);
            $table->boolean('kenal_angka_hitung_5_10')->nullable()->default(false);
            $table->boolean('kenal_warna_warni')->nullable()->default(false);
            $table->boolean('ungkapkan_simpati')->nullable()->default(false);
            $table->boolean('ikut_aturan_permainan')->nullable()->default(false);
            $table->boolean('pakaian_sendiri_tanpa_bantu')->nullable()->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kia_perkembangan_anak_72_bulans');
    }
};
