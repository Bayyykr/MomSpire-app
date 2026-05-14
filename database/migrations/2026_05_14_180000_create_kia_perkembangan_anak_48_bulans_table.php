<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kia_perkembangan_anak_48_bulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->boolean('berdiri_1_kaki_2_detik')->nullable()->default(false);
            $table->boolean('lompat_kedua_kaki')->nullable()->default(false);
            $table->boolean('kayuh_sepeda_roda_3')->nullable()->default(false);
            $table->boolean('gambar_garis_lurus')->nullable()->default(false);
            $table->boolean('tumpuk_8_kubus')->nullable()->default(false);
            $table->boolean('kenal_2_4_warna')->nullable()->default(false);
            $table->boolean('sebut_nama_umur_tempat')->nullable()->default(false);
            $table->boolean('mengerti_arti_kata_posisi')->nullable()->default(false);
            $table->boolean('dengar_cerita')->nullable()->default(false);
            $table->boolean('cuci_tangan_sendiri')->nullable()->default(false);
            $table->boolean('bermain_dengan_teman')->nullable()->default(false);
            $table->boolean('pakai_sepatu_sendiri')->nullable()->default(false);
            $table->boolean('pakai_celana_baju_sendiri')->nullable()->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kia_perkembangan_anak_48_bulans');
    }
};
