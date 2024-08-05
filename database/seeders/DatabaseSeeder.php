<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\city;
use App\Models\nation;
use App\Models\phatmacist;
use App\Models\room;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(adminSeeder::class);
        $this->call(touristSeeder::class);
        $this->call(nationSeeder::class);
        $this->call(citySeeder::class);
        $this->call(servicesSeeder::class);
        $this->call(hotelSeeder::class);
        $this->call(roomSeeder::class);
        $this->call(attraction_activitySeeder::class);
        $this->call(resturantSeeder::class);
        $this->call(tripSeeder::class);
        $this->call(tripReservedSeeder::class);
        // $this->call(hotelReservedSeeder::class);
    }
}
