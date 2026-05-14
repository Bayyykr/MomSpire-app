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
        Schema::create('kia_pemantauan_bulanan_anak_24s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->integer('bulan_ke'); // Bulan ke-12 sampai 23

            $table->boolean('sesak_napas')->default(false);
            $table->boolean('batuk')->default(false);
            $table->boolean('suhu_abnormal')->default(false);
            $table->boolean('bab_sering')->default(false);
            $table->boolean('kencing_sedikit')->default(false);
            $table->boolean('kulit_pucat_biru')->default(false);
            $table->boolean('aktivitas_lemah')->default(false);
            $table->boolean('telinga_cairan')->default(false);
            $table->boolean('tidak_makan')->default(false);

            $table->string('paraf_kader_nakes')->nullable();

            $table->timestamps();

            $table->unique(['data_kia_id', 'bulan_ke']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_pemantauan_bulanan_anak_24s');
    }
};
