<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\city;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            ['City_name' => "Damascus", 'City_Arabic_name' => "دمشق"],
            ['City_name' => "RefDamascus", 'City_Arabic_name' => "ريف دمشق"],
            ['City_name' => "Quneitra", 'City_Arabic_name' => "القنيطرة"],
            ['City_name' => "Daraa", 'City_Arabic_name' => "درعا"],
            ['City_name' => "Al-Suwayda", 'City_Arabic_name' => "السويداء"],
            ['City_name' => "Homs", 'City_Arabic_name' => "حمص"],
            ['City_name' => "Tartus", 'City_Arabic_name' => "طرطوس"],
            ['City_name' => "Latakia", 'City_Arabic_name' => "اللاذقية"],
            ['City_name' => "Hama", 'City_Arabic_name' => "حماه"],
            ['City_name' => "Idlib", 'City_Arabic_name' => "إدلب"],
            ['City_name' => "Aleppo", 'City_Arabic_name' => "حلب"],
            ['City_name' => "Raqqa", 'City_Arabic_name' => "الرقة"],
            ['City_name' => "Deir ez-Zor", 'City_Arabic_name' => "ديرالزور"],
            ['City_name' => "Al-Hasakah", 'City_Arabic_name' => "الحسكة"],
        ];
        foreach ($cities as $city) {
            city::create($city);
        }
    }
}
