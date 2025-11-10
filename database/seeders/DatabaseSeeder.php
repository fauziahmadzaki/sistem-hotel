<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Receptionist',
            'email' => 'receptionist@example.com',
            'password' => Hash::make('receptionist'),
            'role' => 'receptionist',
        ]);

        $this->call([
            RoomTypeSeeder::class,
            FacilitySeeder::class,
            RoomSeeder::class,
        ]);
    }
}