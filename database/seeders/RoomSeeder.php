<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\RoomType;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil Tipe Kamar
        $standard = RoomType::where('room_type_name', 'Standard')->first();
        $deluxe = RoomType::where('room_type_name', 'Deluxe')->first();
        $suite = RoomType::where('room_type_name', 'Suite')->first();

        $rooms = [
            // Standard Rooms
            [
                'room_type_id' => $standard->id,
                'room_name' => 'Standard Room 101',
                'room_code' => 'STD-101',
                'room_description' => 'Kamar standar di lantai 1 dekat lobby.',
                'room_capacity' => 2, 'room_price' => 500000, 'room_status' => 'available',
            ],
            [
                'room_type_id' => $standard->id,
                'room_name' => 'Standard Room 102',
                'room_code' => 'STD-102',
                'room_description' => 'Kamar standar di lantai 1 dekat lobby.',
                'room_capacity' => 2, 'room_price' => 500000, 'room_status' => 'available',
            ],
            [
                'room_type_id' => $standard->id,
                'room_name' => 'Standard Room 103',
                'room_code' => 'STD-103',
                'room_description' => 'Kamar standar di lantai 1.',
                'room_capacity' => 2, 'room_price' => 500000, 'room_status' => 'booked', // Untuk testing
            ],

            // Deluxe Rooms
            [
                'room_type_id' => $deluxe->id,
                'room_name' => 'Deluxe Room 201',
                'room_code' => 'DLX-201',
                'room_description' => 'Kamar deluxe di lantai 2 dengan pemandangan kota.',
                'room_capacity' => 2, 'room_price' => 850000, 'room_status' => 'available',
            ],
            [
                'room_type_id' => $deluxe->id,
                'room_name' => 'Deluxe Room 202',
                'room_code' => 'DLX-202',
                'room_description' => 'Kamar deluxe di lantai 2 dengan pemandangan kota.',
                'room_capacity' => 2, 'room_price' => 850000, 'room_status' => 'maintenance', // Untuk testing
            ],

            // Suite Rooms
            [
                'room_type_id' => $suite->id,
                'room_name' => 'Presidential Suite 301',
                'room_code' => 'SUI-301',
                'room_description' => 'Suite mewah di lantai 3 dengan pemandangan laut.',
                'room_capacity' => 4, 'room_price' => 1500000, 'room_status' => 'available',
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}