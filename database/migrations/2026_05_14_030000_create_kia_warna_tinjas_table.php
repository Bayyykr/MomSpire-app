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
        Schema::create('kia_warna_tinjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            
            $table->string('tanggal_2_minggu')->nullable();
            $table->integer('nomor_2_minggu')->nullable();
            
            $table->string('tanggal_1_bulan')->nullable();
            $table->integer('nomor_1_bulan')->nullable();
            
            $table->string('tanggal_2_4_bulan')->nullable();
            $table->integer('nomor_2_4_bulan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_warna_tinjas');
    }
};
