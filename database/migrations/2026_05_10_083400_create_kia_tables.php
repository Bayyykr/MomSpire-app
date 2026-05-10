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
        // 1. Core Data KIA Table
        Schema::create('data_kias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('faskes_dikeluarkan')->nullable();
            $table->date('tanggal_dikeluarkan')->nullable();
            $table->string('kab_kota_dikeluarkan')->nullable();
            $table->string('provinsi_dikeluarkan')->nullable();
            $table->timestamps();
        });

        // 2. Identitas Ibu
        Schema::create('kia_identitas_ibu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->string('nama')->nullable();
            $table->string('nik')->nullable();
            $table->string('no_jkn')->nullable();
            $table->string('faskes_tk1')->nullable();
            $table->string('faskes_rujukan')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('golongan_darah')->nullable();
            $table->timestamps();
        });

        // 3. Identitas Suami
        Schema::create('kia_identitas_suami', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->string('nama')->nullable();
            $table->string('nik')->nullable();
            $table->string('no_jkn')->nullable();
            $table->string('faskes_tk1')->nullable();
            $table->string('faskes_rujukan')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('golongan_darah')->nullable();
            $table->timestamps();
        });

        // 4. Identitas Anak
        Schema::create('kia_identitas_anak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->string('nama')->nullable();
            $table->string('nik')->nullable();
            $table->string('no_jkn')->nullable();
            $table->string('faskes_tk1')->nullable();
            $table->string('faskes_rujukan')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('anak_ke')->nullable();
            $table->string('no_akta_kelahiran')->nullable();
            $table->string('golongan_darah')->nullable();
            $table->timestamps();
        });

        // 5. Layanan & Pembiayaan
        Schema::create('kia_layanan_pembiayaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->string('puskesmas_domisili')->nullable();
            $table->string('no_reg_kohort_ibu')->nullable();
            $table->string('no_reg_kohort_bayi')->nullable();
            $table->string('no_reg_kohort_balita')->nullable();
            $table->string('no_catatan_medik_rs')->nullable();
            $table->string('asuransi_lain')->nullable();
            $table->string('no_asuransi_lain')->nullable();
            $table->date('tanggal_berlaku_asuransi_lain')->nullable();
            $table->timestamps();
        });

        // 6. Riwayat Kesehatan & Kehamilan
        Schema::create('kia_riwayat_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->integer('usia_ibu')->nullable();
            $table->string('kehamilan_ke')->nullable();
            $table->integer('jumlah_anak_hidup')->nullable();
            $table->integer('riwayat_keguguran')->nullable();
            $table->text('riwayat_penyakit_ibu')->nullable();
            $table->date('hpht')->nullable();
            $table->date('htp')->nullable();
            $table->decimal('lingkar_lengan_atas', 5, 2)->nullable();
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->text('trimester_1')->nullable();
            $table->text('trimester_2')->nullable();
            $table->text('trimester_3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_riwayat_kesehatan');
        Schema::dropIfExists('kia_layanan_pembiayaan');
        Schema::dropIfExists('kia_identitas_anak');
        Schema::dropIfExists('kia_identitas_suami');
        Schema::dropIfExists('kia_identitas_ibu');
        Schema::dropIfExists('data_kias');
    }
};
