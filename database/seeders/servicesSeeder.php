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
            ['service' => "good breakfast"],
        ];
        foreach ($services as $service) {
            service::create($service);
        }
    }
}
