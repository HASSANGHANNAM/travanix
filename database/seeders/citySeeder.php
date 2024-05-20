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
            ['city_name_in_english' => "Damascus", 'city_name_in_arabic' => "دمشق", 'nation_id' => 1],
            ['city_name_in_english' => "RefDamascus", 'city_name_in_arabic' => "ريف دمشق", 'nation_id' => 1],
            ['city_name_in_english' => "Quneitra", 'city_name_in_arabic' => "القنيطرة", 'nation_id' => 1],
            ['city_name_in_english' => "Daraa", 'city_name_in_arabic' => "درعا", 'nation_id' => 1],
            ['city_name_in_english' => "Al-Suwayda", 'city_name_in_arabic' => "السويداء", 'nation_id' => 1],
            ['city_name_in_english' => "Homs", 'city_name_in_arabic' => "حمص", 'nation_id' => 1],
            ['city_name_in_english' => "Tartus", 'city_name_in_arabic' => "طرطوس", 'nation_id' => 1],
            ['city_name_in_english' => "Latakia", 'city_name_in_arabic' => "اللاذقية", 'nation_id' => 1],
            ['city_name_in_english' => "Hama", 'city_name_in_arabic' => "حماه", 'nation_id' => 1],
            ['city_name_in_english' => "Idlib", 'city_name_in_arabic' => "إدلب", 'nation_id' => 1],
            ['city_name_in_english' => "Aleppo", 'city_name_in_arabic' => "حلب", 'nation_id' => 1],
            ['city_name_in_english' => "Raqqa", 'city_name_in_arabic' => "الرقة", 'nation_id' => 1],
            ['city_name_in_english' => "Deir ez-Zor", 'city_name_in_arabic' => "ديرالزور", 'nation_id' => 1],
            ['city_name_in_english' => "Al-Hasakah", 'city_name_in_arabic' => "الحسكة", 'nation_id' => 1],
        ];
        foreach ($cities as $city) {
            city::create($city);
        }
    }
}
