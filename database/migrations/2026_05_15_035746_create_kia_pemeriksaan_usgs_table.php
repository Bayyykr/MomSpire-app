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
        Schema::create('kia_pemeriksaan_usgs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->cascadeOnDelete();
            $table->string('gambar_usg')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('jenis_kegiatan', ['pemeriksaan', 'skrining'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_pemeriksaan_usgs');
    }
};
