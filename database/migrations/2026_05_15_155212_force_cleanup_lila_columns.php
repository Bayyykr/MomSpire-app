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
            $cols = ['lila_status', 'lila_kek', 'lila_normal', 'imt_kurus', 'imt_normal', 'imt_gemuk', 'imt_obesitas', 'lila_kurus', 'lila_gemuk', 'lila_obesitas'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('kia_evaluasi_kesehatan_ibus', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert cleanup
    }
};
