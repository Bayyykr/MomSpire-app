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
        Schema::create('kia_absen_kelas_ibu_hamils', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $blueprint->integer('kehadiran_ke'); // 1 to 9
            $blueprint->string('tanggal')->nullable();
            $blueprint->string('kader_info')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_absen_kelas_ibu_hamils');
    }
};
