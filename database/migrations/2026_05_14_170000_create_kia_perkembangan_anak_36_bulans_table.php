<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kia_perkembangan_anak_36_bulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->boolean('naik_tangga')->nullable()->default(false);
            $table->boolean('tendang_bola')->nullable()->default(false);
            $table->boolean('coret_kertas')->nullable()->default(false);
            $table->boolean('bicara_2_kata')->nullable()->default(false);
            $table->boolean('tunjuk_bagian_tubuh')->nullable()->default(false);
            $table->boolean('sebut_nama_benda')->nullable()->default(false);
            $table->boolean('pungut_mainan')->nullable()->default(false);
            $table->boolean('makan_nasi_sendiri')->nullable()->default(false);
            $table->boolean('lepas_pakaian')->nullable()->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kia_perkembangan_anak_36_bulans');
    }
};
