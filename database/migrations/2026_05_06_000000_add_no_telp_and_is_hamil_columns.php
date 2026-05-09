<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'no_telp')) {
                $table->string('no_telp', 30)->nullable()->after('email');
            }
        });

        Schema::table('admin', function (Blueprint $table) {
            if (!Schema::hasColumn('admin', 'no_telp')) {
                $table->string('no_telp', 30)->nullable()->after('email');
            }
        });

        Schema::table('pengguna', function (Blueprint $table) {
            if (!Schema::hasColumn('pengguna', 'is_hamil')) {
                $table->boolean('is_hamil')->default(false)->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            if (Schema::hasColumn('pengguna', 'is_hamil')) {
                $table->dropColumn('is_hamil');
            }
        });

        Schema::table('admin', function (Blueprint $table) {
            if (Schema::hasColumn('admin', 'no_telp')) {
                $table->dropColumn('no_telp');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'no_telp')) {
                $table->dropColumn('no_telp');
            }
        });
    }
};