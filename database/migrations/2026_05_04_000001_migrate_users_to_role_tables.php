<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate pengguna
        $pengguna = DB::table('users')->where('role', 'pengguna')->get();
        foreach ($pengguna as $user) {
            DB::table('pengguna')->insert([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'remember_token' => $user->remember_token,
                'current_team_id' => $user->current_team_id,
                'profile_photo_path' => $user->profile_photo_path,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        // Migrate bidan
        $bidan = DB::table('users')->where('role', 'bidan')->get();
        foreach ($bidan as $user) {
            DB::table('bidan')->insert([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'remember_token' => $user->remember_token,
                'current_team_id' => $user->current_team_id,
                'profile_photo_path' => $user->profile_photo_path,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        // Migrate dokter
        $dokter = DB::table('users')->where('role', 'dokter')->get();
        foreach ($dokter as $user) {
            DB::table('dokter')->insert([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'remember_token' => $user->remember_token,
                'current_team_id' => $user->current_team_id,
                'profile_photo_path' => $user->profile_photo_path,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        // Migrate admin
        $admin = DB::table('users')->where('role', 'admin')->get();
        foreach ($admin as $user) {
            DB::table('admin')->insert([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'remember_token' => $user->remember_token,
                'current_team_id' => $user->current_team_id,
                'profile_photo_path' => $user->profile_photo_path,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bisa di-reverse jika diperlukan
    }
};
