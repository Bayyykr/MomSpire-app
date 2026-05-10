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
        Schema::table('kia_layanan_pembiayaan', function (Blueprint $table) {
            // Suami
            $table->string('asuransi_suami')->nullable();
            $table->string('no_asuransi_suami')->nullable();
            $table->date('tanggal_berlaku_asuransi_suami')->nullable();
            $table->string('puskesmas_domisili_suami')->nullable();
            $table->string('no_catatan_medik_rs_suami')->nullable();

            // Anak
            $table->string('asuransi_anak')->nullable();
            $table->string('no_asuransi_anak')->nullable();
            $table->date('tanggal_berlaku_asuransi_anak')->nullable();
            $table->string('puskesmas_domisili_anak')->nullable();
            $table->string('no_catatan_medik_rs_anak')->nullable();
        });

        Schema::table('kia_identitas_anak', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('golongan_darah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kia_layanan_pembiayaan', function (Blueprint $table) {
            $table->dropColumn([
                'asuransi_suami', 'no_asuransi_suami', 'tanggal_berlaku_asuransi_suami',
                'puskesmas_domisili_suami', 'no_catatan_medik_rs_suami',
                'asuransi_anak', 'no_asuransi_anak', 'tanggal_berlaku_asuransi_anak',
                'puskesmas_domisili_anak', 'no_catatan_medik_rs_anak'
            ]);
        });

        Schema::table('kia_identitas_anak', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });
    }
};
