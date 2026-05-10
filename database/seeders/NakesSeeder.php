<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class NakesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Bidan Dummy
        $bidanUser = User::create([
            'name' => 'Bidan Siti',
            'email' => 'bidan@momspire.com',
            'role' => 'bidan',
            'password' => Hash::make('password'),
        ]);

        DB::table('bidan')->insert([
            'id' => $bidanUser->id,
            'name' => 'Bidan Siti',
            'email' => 'bidan@momspire.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Create Dokter Dummy
        $dokterUser = User::create([
            'name' => 'Dr. Ahmad',
            'email' => 'dokter@momspire.com',
            'role' => 'dokter',
            'password' => Hash::make('password'),
        ]);

        DB::table('dokter')->insert([
            'id' => $dokterUser->id,
            'name' => 'Dr. Ahmad',
            'email' => 'dokter@momspire.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "Dummy Bidan & Dokter created successfully!\n";
        echo "Email Bidan: bidan@momspire.com\n";
        echo "Email Dokter: dokter@momspire.com\n";
        echo "Password: password\n";
    }
}
