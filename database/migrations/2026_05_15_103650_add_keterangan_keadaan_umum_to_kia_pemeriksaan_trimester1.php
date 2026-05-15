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
        Schema::table('kia_pemeriksaan_trimester1', function (Blueprint $table) {
            $table->string('keterangan_konjungtiva')->nullable();
            $table->string('keterangan_sklera')->nullable();
            $table->string('keterangan_kulit')->nullable();
            $table->string('keterangan_leher')->nullable();
            $table->string('keterangan_gigi_mulut')->nullable();
            $table->string('keterangan_tht')->nullable();
            $table->string('keterangan_dada_jantung')->nullable();
            $table->string('keterangan_dada_paru')->nullable();
            $table->string('keterangan_perut')->nullable();
            $table->string('keterangan_tungkai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kia_pemeriksaan_trimester1', function (Blueprint $table) {
            $table->dropColumn([
                'keterangan_konjungtiva', 'keterangan_sklera', 'keterangan_kulit',
                'keterangan_leher', 'keterangan_gigi_mulut', 'keterangan_tht',
                'keterangan_dada_jantung', 'keterangan_dada_paru', 'keterangan_perut',
                'keterangan_tungkai'
            ]);
        });
    }
};
