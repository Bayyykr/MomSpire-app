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
        Schema::table('kia_evaluasi_kesehatan_ibus', function (Blueprint $table) {
            $table->float('lila_kek')->nullable()->after('lila');
            $table->float('lila_normal')->nullable()->after('lila_kek');
            $table->float('imt_kurus')->nullable()->after('imt');
            $table->float('imt_normal')->nullable()->after('imt_kurus');
            $table->float('imt_gemuk')->nullable()->after('imt_normal');
            $table->float('imt_obesitas')->nullable()->after('imt_gemuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kia_evaluasi_kesehatan_ibus', function (Blueprint $table) {
            $table->dropColumn(['lila_kek', 'lila_normal', 'imt_kurus', 'imt_normal', 'imt_gemuk', 'imt_obesitas']);
        });
    }
};
