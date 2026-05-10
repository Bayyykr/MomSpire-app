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
        Schema::table('kia_identitas_anak', function (Blueprint $table) {
            $table->string('telepon')->nullable()->after('no_akta_kelahiran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kia_identitas_anak', function (Blueprint $table) {
            $table->dropColumn('telepon');
        });
    }
};
