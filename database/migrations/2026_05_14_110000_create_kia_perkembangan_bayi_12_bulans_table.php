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
        Schema::create('kia_perkembangan_bayi_12_bulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');

            $table->boolean('angkat_badan_berdiri')->nullable();
            $table->boolean('belajar_berdiri')->nullable();
            $table->boolean('jalan_dituntun')->nullable();
            $table->boolean('ulur_tangan_raih')->nullable();
            $table->boolean('genggam_pensil')->nullable();
            $table->boolean('masuk_benda_mulut')->nullable();
            $table->boolean('tiru_bunyi')->nullable();
            $table->boolean('sebut_2_suku_kata')->nullable();
            $table->boolean('eksplorasi_sekitar')->nullable();
            $table->boolean('reaksi_panggilan')->nullable();
            $table->boolean('bermain_cilukba')->nullable();
            $table->boolean('kenal_keluarga')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_perkembangan_bayi_12_bulans');
    }
};
