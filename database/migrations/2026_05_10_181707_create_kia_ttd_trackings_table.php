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
        Schema::create('kia_ttd_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            $table->integer('bulan_ke'); // 1-10
            $table->string('usia_kehamilan')->nullable();
            $table->string('bulan_tahun')->nullable();
            for ($i = 1; $i <= 31; $i++) {
                $table->boolean("h$i")->default(false);
            }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_ttd_trackings');
    }
};
