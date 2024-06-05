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
            ['city_name' => "Amman", 'nation_id' => 2],
            ['city_name' => "Irbid", 'nation_id' => 2],
            ['city_name' => "ar-Rusaifa", 'nation_id' => 2],
            ['city_name' => "al-Quwaisima", 'nation_id' => 2],
            ['city_name' => "Wadi as-Sir", 'nation_id' => 2],
            ['city_name' => "Atlanta", 'nation_id' => 3],
            ['city_name' => "Boston", 'nation_id' => 3],
            ['city_name' => "Chicago", 'nation_id' => 3],
            ['city_name' => "Dallas", 'nation_id' => 3],
            ['city_name' => "Detroit", 'nation_id' => 3],
            ['city_name' => "Honolulu", 'nation_id' => 3],
            ['city_name' => "Houston", 'nation_id' => 3],
            ['city_name' => "Las Vegas", 'nation_id' => 3],
            ['city_name' => "Los Angeles", 'nation_id' => 3],
            ['city_name' => "Memphis", 'nation_id' => 3],
            ['city_name' => "Miami", 'nation_id' => 3],
            ['city_name' => "Nashville", 'nation_id' => 3],
            ['city_name' => "New Orleans", 'nation_id' => 3],
            ['city_name' => "Philadelphia", 'nation_id' => 3],
            ['city_name' => "Phoenix", 'nation_id' => 3],
            ['city_name' => "San Antonio", 'nation_id' => 3],
            ['city_name' => "San Diego", 'nation_id' => 3],
            ['city_name' => "Dubai", 'nation_id' => 225],
        ];
        foreach ($cities as $city) {
            city::create($city);
        }
    }
}
