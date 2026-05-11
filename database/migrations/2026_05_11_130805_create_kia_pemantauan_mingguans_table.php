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
        Schema::create('kia_pemantauan_mingguans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->integer('minggu_ke'); // Minggu ke-4 sampai ke-42

            // Pelayanan Kesehatan
            $table->boolean('pemeriksaan_kehamilan')->default(false);
            $table->boolean('kelas_ibu_hamil')->default(false);

            // Pemantauan Mingguan
            $table->boolean('demam_lebih_2_hari')->default(false);
            $table->boolean('pusing_sakit_kepala')->default(false);
            $table->boolean('sulit_tidur_cemas')->default(false);
            $table->boolean('risiko_tb')->default(false);
            $table->boolean('gerakan_bayi')->default(false);
            $table->boolean('nyeri_perut_hebat')->default(false);
            $table->boolean('keluar_cairan_lahir')->default(false);
            $table->boolean('sakit_saat_kencing')->default(false);
            $table->boolean('diare_berulang')->default(false);

            $table->timestamps();

            // Unique constraint to prevent duplicate entry for same week of same KIA record
            $table->unique(['data_kia_id', 'minggu_ke']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_pemantauan_mingguans');
    }
};
