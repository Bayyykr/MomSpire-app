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
        Schema::create('kia_perkembangan_bayis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');

            $table->boolean('angkat_kepala_45')->nullable();
            $table->boolean('gerak_kepala')->nullable();
            $table->boolean('tatap_wajah')->nullable();
            $table->boolean('ngoceh')->nullable();
            $table->boolean('tertawa_keras')->nullable();
            $table->boolean('terkejut_suara')->nullable();
            $table->boolean('tersenyum')->nullable();
            $table->boolean('mengenal_ibu')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_perkembangan_bayis');
    }
};
