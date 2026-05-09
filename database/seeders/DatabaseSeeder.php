<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create single admin account (fixed credentials)
        $adminEmail = 'admin@gmail.com';
        $adminPassword = 'admin123';

        if (!User::where('email', $adminEmail)->exists()) {
            User::factory()->create([
                'name' => 'Administrator',
                'email' => $adminEmail,
                'password' => bcrypt($adminPassword),
                'role' => 'admin',
            ]);
        }

        // Optional: keep one test pengguna account
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => 'pengguna',
            ]);
        }

        // Create specific example accounts requested
        $examples = [
            ['role' => 'pengguna', 'email' => 'pengguna@gmail.com', 'password' => 'pengguna123', 'name' => 'Pengguna Contoh'],
            ['role' => 'bidan', 'email' => 'bidan@gmail.com', 'password' => 'bidan123', 'name' => 'Bidan Contoh'],
            ['role' => 'dokter', 'email' => 'dokter@gmail.com', 'password' => 'dokter123', 'name' => 'Dokter Contoh'],
        ];

        foreach ($examples as $ex) {
            if (!User::where('email', $ex['email'])->exists()) {
                $user = User::create([
                    'name' => $ex['name'],
                    'email' => $ex['email'],
                    'password' => bcrypt($ex['password']),
                    'role' => $ex['role'],
                ]);

                // Also insert into role-specific table so admin panels see them
                $roleTable = $ex['role'];
                $insert = [
                    'id' => $user->id,
                    'name' => $ex['name'],
                    'email' => $ex['email'],
                    'password' => bcrypt($ex['password']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if ($roleTable === 'pengguna') {
                    $insert['is_hamil'] = 0;
                }

                \Illuminate\Support\Facades\DB::table($roleTable)->insert($insert);
            }
        }
    }
}
