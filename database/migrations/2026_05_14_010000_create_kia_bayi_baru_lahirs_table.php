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
        Schema::create('kia_bayi_baru_lahirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kia_id')->constrained('data_kias')->onDelete('cascade');
            
            $table->boolean('jam_0_6')->default(false);
            $table->boolean('jam_6_48')->default(false);
            $table->boolean('hari_3_7')->default(false);
            $table->boolean('hari_8_28')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kia_bayi_baru_lahirs');
    }
};
