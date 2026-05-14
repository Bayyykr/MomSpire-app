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
        Schema::dropIfExists('kia_proses_melahirkans');

        if (Schema::hasTable('kia_keluarga_berencanas')) {
            Schema::table('kia_keluarga_berencanas', function (Blueprint $table) {
                if (Schema::hasColumn('kia_keluarga_berencanas', 'mengapa_ikut_kb')) {
                    $table->dropColumn([
                        'mengapa_ikut_kb',
                        'metode_kontrasepsi_jp',
                        'non_metode_kontrasepsi_jp',
                        'bersedia_kb_pasca_salin',
                    ]);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('kia_proses_melahirkans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->boolean('mulas_teratur')->default(false);
            $table->boolean('durasi_persalinan')->default(false);
            $table->boolean('hak_pendamping')->default(false);
            $table->boolean('hak_posisi')->default(false);
            $table->boolean('ingin_bab')->default(false);
            $table->boolean('kurangi_sakit')->default(false);
            $table->boolean('inisiasi_menyusu_dini')->default(false);
            $table->timestamps();
        });

        if (Schema::hasTable('kia_keluarga_berencanas')) {
            Schema::table('kia_keluarga_berencanas', function (Blueprint $table) {
                if (!Schema::hasColumn('kia_keluarga_berencanas', 'mengapa_ikut_kb')) {
                    $table->boolean('mengapa_ikut_kb')->default(false);
                    $table->boolean('metode_kontrasepsi_jp')->default(false);
                    $table->boolean('non_metode_kontrasepsi_jp')->default(false);
                    $table->boolean('bersedia_kb_pasca_salin')->default(false);
                }
            });
        }
    }
};
