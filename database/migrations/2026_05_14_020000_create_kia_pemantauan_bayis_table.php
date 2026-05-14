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
        Schema::create('kia_pemantauan_bayis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->integer('hari_ke');

            // Halaman 23 (Section A)
            $table->boolean('sesak_napas')->default(false);
            $table->boolean('aktivitas_lemah')->default(false);
            $table->boolean('warna_kulit_biru')->default(false);
            $table->boolean('hisapan_lemah')->default(false);
            $table->boolean('kejang')->default(false);
            $table->boolean('suhu_abnormal')->default(false);

            // Halaman 24 (Section B)
            $table->boolean('bab_abnormal')->default(false);
            $table->boolean('kencing_sedikit')->default(false);
            $table->boolean('tali_pusat_merah')->default(false);
            $table->boolean('mata_merah')->default(false);
            $table->boolean('kulit_bintil')->default(false);
            $table->boolean('belum_imunisasi')->default(false);

            $table->string('paraf_kader_nakes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_pemantauan_bayis');
    }
};
