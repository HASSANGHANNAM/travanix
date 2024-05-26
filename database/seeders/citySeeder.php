<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\city;

class citySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            ['city_name' => "Damascus", 'nation_id' => 1],
            ['city_name' => "RefDamascus", 'nation_id' => 1],
            ['city_name' => "Quneitra", 'nation_id' => 1],
            ['city_name' => "Daraa", 'nation_id' => 1],
            ['city_name' => "Al-Suwayda", 'nation_id' => 1],
            ['city_name' => "Homs", 'nation_id' => 1],
            ['city_name' => "Tartus", 'nation_id' => 1],
            ['city_name' => "Latakia", 'nation_id' => 1],
            ['city_name' => "Hama", 'nation_id' => 1],
            ['city_name' => "Idlib", 'nation_id' => 1],
            ['city_name' => "Aleppo", 'nation_id' => 1],
            ['city_name' => "Raqqa", 'nation_id' => 1],
            ['city_name' => "Deir ez-Zor", 'nation_id' => 1],
            ['city_name' => "Al-Hasakah", 'nation_id' => 1],
            ['city_name' => "Al_Zarqa", 'nation_id' => 2],
        ];
        foreach ($cities as $city) {
            // city::create($city);
        }
    }
}
