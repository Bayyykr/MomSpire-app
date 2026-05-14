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
        Schema::dropIfExists('kia_perkembangan_anak_2_tahuns');
        Schema::dropIfExists('kia_perkembangan_anak_3_tahuns');

        Schema::create('kia_perkembangan_bayi_18_bulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');

            $table->boolean('berdiri_tanpa_pegangan')->nullable();
            $table->boolean('bungkuk_pungut_mainan')->nullable();
            $table->boolean('jalan_mundur_5_langkah')->nullable();
            $table->boolean('panggil_papa_mama')->nullable();
            $table->boolean('tumpuk_2_kubus')->nullable();
            $table->boolean('masuk_kubus_kotak')->nullable();
            $table->boolean('tunjuk_tanpa_nangis')->nullable();
            $table->boolean('rasa_cemburu')->nullable();

            $table->timestamps();
        });

        Schema::create('kia_perkembangan_bayi_24_bulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');

            $table->boolean('berdiri_30_detik')->nullable();
            $table->boolean('jalan_tanpa_huyung')->nullable();
            $table->boolean('tumpuk_4_kubus')->nullable();
            $table->boolean('pungut_benda_kecil')->nullable();
            $table->boolean('gelinding_bola')->nullable();
            $table->boolean('sebut_3_6_kata')->nullable();
            $table->boolean('bantu_pekerjaan_rumah')->nullable();
            $table->boolean('pegang_cangkir_sendiri')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_perkembangan_bayi_18_bulans');
        Schema::dropIfExists('kia_perkembangan_bayi_24_bulans');
    }
};
