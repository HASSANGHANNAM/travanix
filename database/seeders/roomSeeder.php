<?php

namespace Database\Seeders;

use App\Models\hotel;
use App\Models\room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class roomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            [
                "capacity_room" => 1,
                "price_room" => 150,
                "quantity" => 5,
                "hotel_id" => 1
            ], [
                "capacity_room" => 2,
                "price_room" => 280,
                "quantity" => 10,
                "hotel_id" => 1
            ], [
                "capacity_room" => 3,
                "price_room" => 400,
                "quantity" => 10,
                "hotel_id" => 1
            ], [
                "capacity_room" => 1,
                "price_room" => 130,
                "quantity" => 10,
                "hotel_id" => 2
            ], [
                "capacity_room" => 2,
                "price_room" => 200,
                "quantity" => 10,
                "hotel_id" => 2
            ], [
                "capacity_room" => 4,
                "price_room" => 390,
                "quantity" => 10,
                "hotel_id" => 2
            ], [
                "capacity_room" => 1,
                "price_room" => 200,
                "quantity" => 10,
                "hotel_id" => 3
            ], [
                "capacity_room" => 2,
                "price_room" => 380,
                "quantity" => 10,
                "hotel_id" => 3
            ], [
                "capacity_room" => 3,
                "price_room" => 500,
                "quantity" => 10,
                "hotel_id" => 3
            ]
        ];
        foreach ($rooms as $room) {
            room::create($room);
        }
    }
}
