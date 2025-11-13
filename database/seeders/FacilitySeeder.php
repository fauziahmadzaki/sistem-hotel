<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            ['facility_name' => 'WiFi Gratis'],
            ['facility_name' => 'AC'],
            ['facility_name' => 'TV 42 Inch'],
            ['facility_name' => 'Kamar Mandi Shower'],
            ['facility_name' => 'Kamar Mandi Bathtub'],
            ['facility_name' => 'Sarapan Gratis'],
            ['facility_name' => 'Kopi & Teh'],
            ['facility_name' => 'Minibar'],
            ['facility_name' => 'Balkon Pribadi'],
            ['facility_name' => 'Pemandangan Laut'],
        ];

        // Kosongkan tabel dulu jika perlu
        // DB::table('facilities')->truncate();

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}