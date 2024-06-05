<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\city;
use App\Models\service;

class servicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            ['service' => "Free wifi"],
            ['service' => "Parking free"],
            ['service' => "Buffer dinner"],
            ['service' => "Breakfast"],
            ['service' => "Credit Cards"],
            ['service' => "NFC mobile payments"]
        ];
        foreach ($services as $service) {
            service::create($service);
        }
    }
}
