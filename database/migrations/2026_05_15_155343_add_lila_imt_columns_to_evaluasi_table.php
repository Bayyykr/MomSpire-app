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
            $table->float('lila_kurus')->nullable()->after('lila');
            $table->float('lila_normal')->nullable()->after('lila_kurus');
            $table->float('lila_gemuk')->nullable()->after('lila_normal');
            $table->float('lila_obesitas')->nullable()->after('lila_gemuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kia_evaluasi_kesehatan_ibus', function (Blueprint $table) {
            $table->dropColumn(['lila_kurus', 'lila_normal', 'lila_gemuk', 'lila_obesitas']);
        });
    }
};
