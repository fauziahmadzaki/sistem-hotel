<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::insert([
            [
                'id' => 1,
                'room_name' => 'Deluxe Ocean View',
                'room_code' => 'DLX-001',
                'room_description' => 'Kamar dengan pemandangan laut, fasilitas lengkap dan balkon pribadi.',
                'room_capacity' => 2,
                'room_price' => 750000,
                'image' => 'rooms/deluxe-ocean-view.jpg',
                'room_status' => 'available',
                'room_type_id' => 1,
            ],
            [
                'id' => 2,
                'room_name' => 'Super Family Suite',
                'room_code' => 'SPR-002',
                'room_description' => 'Kamar besar cocok untuk keluarga dengan ruang tamu dan dapur mini.',
                'room_capacity' => 4,
                'room_price' => 1200000,
                'image' => 'rooms/super-family-suite.jpg',
                'room_status' => 'available',
                'room_type_id' => 2, 
            ],
            [
                'id' => 3,
                'room_name' => 'Deluxe Garden View',
                'room_code' => 'DLX-003',
                'room_description' => 'Kamar nyaman dengan pemandangan taman dan akses langsung ke kolam renang.',
                'room_capacity' => 2,
                'room_price' => 680000,
                'image' => 'rooms/deluxe-garden-view.jpg',
                'room_status' => 'maintenance',
                'room_type_id' => 1, 
            ],
        ]);
    }
}