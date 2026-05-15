<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kia_pemeriksaan_trimester2', function (Blueprint $table) {
            $table->string('tindak_lanjut_puasa')->nullable()->after('lab_gula_darah_puasa');
            $table->string('tindak_lanjut_2jam')->nullable()->after('lab_gula_darah_2jam');
        });
    }

    public function down(): void
    {
        Schema::table('kia_pemeriksaan_trimester2', function (Blueprint $table) {
            $table->dropColumn(['tindak_lanjut_puasa', 'tindak_lanjut_2jam']);
        });
    }
};
