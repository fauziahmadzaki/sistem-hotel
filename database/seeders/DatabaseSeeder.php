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
            'name' => 'Fauzi Ahmad Zaki',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
        ]);
        User::create([
            'name' => 'Enggar Punto Adi',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Enggar Permadi',
            'username' => 'housekeeper',
            'email' => 'housekeeper@example.com',
            'password' => Hash::make('password123'),
            'role' => 'housekeeper',
        ]);


        $this->call([
            FacilitySeeder::class,
            RoomTypeSeeder::class,
            RoomSeeder::class,
        ]);
    }
}