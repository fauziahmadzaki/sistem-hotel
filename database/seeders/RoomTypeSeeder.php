<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomType;
use App\Models\Facility;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID fasilitas untuk di-attach
        $wifi = Facility::where('facility_name', 'WiFi Gratis')->first()->id;
        $ac = Facility::where('facility_name', 'AC')->first()->id;
        $tv = Facility::where('facility_name', 'TV 42 Inch')->first()->id;
        $shower = Facility::where('facility_name', 'Kamar Mandi Shower')->first()->id;
        $bathtub = Facility::where('facility_name', 'Kamar Mandi Bathtub')->first()->id;
        $sarapan = Facility::where('facility_name', 'Sarapan Gratis')->first()->id;
        $kopiTeh = Facility::where('facility_name', 'Kopi & Teh')->first()->id;
        $minibar = Facility::where('facility_name', 'Minibar')->first()->id;
        $balkon = Facility::where('facility_name', 'Balkon Pribadi')->first()->id;
        $pemandangan = Facility::where('facility_name', 'Pemandangan Laut')->first()->id;

        // 1. Tipe Standard
        $standard = RoomType::create([
            'room_type_name' => 'Standard',
            'description' => 'Kamar standar yang nyaman dengan fasilitas dasar untuk 2 orang.'
        ]);
        // Attach fasilitas ke Tipe Standard
        $standard->facilities()->attach([$wifi, $ac, $tv, $shower, $kopiTeh]);

        // 2. Tipe Deluxe
        $deluxe = RoomType::create([
            'room_type_name' => 'Deluxe',
            'description' => 'Kamar lebih luas dengan pemandangan kota dan sarapan gratis.'
        ]);
        // Attach fasilitas ke Tipe Deluxe
        $deluxe->facilities()->attach([$wifi, $ac, $tv, $shower, $kopiTeh, $minibar, $sarapan]);

        // 3. Tipe Suite
        $suite = RoomType::create([
            'room_type_name' => 'Suite',
            'description' => 'Kamar termewah dengan ruang tamu terpisah, bathtub, dan balkon pribadi.'
        ]);
        // Attach fasilitas ke Tipe Suite
        $suite->facilities()->attach([$wifi, $ac, $tv, $bathtub, $kopiTeh, $minibar, $sarapan, $balkon, $pemandangan]);
    }
}