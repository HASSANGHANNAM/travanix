<?php

namespace Database\Seeders;

use App\Models\reserve;
use App\Models\reserve_has_room;
use App\Models\tourist_details;
use App\Models\tourist_has_trip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class hotelReservedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reserves = [
            [
                "hotel_id" => 1,
                "start_reservation" => "2024-09-01",
                "end_reservation" => "2024-09-03",
                "status" => "Pending",
                "rooms" => [
                    [
                        "number_of_room" => 3,
                        "capacity_room" => 2
                    ],
                    [
                        "number_of_room" => 1,
                        "capacity_room" => 1
                    ]
                ]
            ],
            [
                "hotel_id" => 2,
                "start_reservation" => "2024-10-01",
                "end_reservation" => "2024-10-03",
                "status" => "Canceled",
                "rooms" => [
                    [
                        "number_of_room" => 3,
                        "capacity_room" => 2
                    ],  [
                        "number_of_room" => 1,
                        "capacity_room" => 1
                    ],
                    [
                        "number_of_room" => 1,
                        "capacity_room" => 4
                    ]
                ]
            ],
            [
                "hotel_id" => 3,
                "start_reservation" => "2024-11-01",
                "end_reservation" => "2024-11-03",
                "status" => "Submitted",
                "rooms" => [
                    [
                        "number_of_room" => 1,
                        "capacity_room" => 3
                    ]
                ]
            ]
        ];
        foreach ($reserves as $reserve) {
            $startDate = $reserve['start_reservation'];
            $endDate = $reserve['end_reservation'];
            $price_all_reserve = 0;
            foreach ($reserve['rooms'] as $room) {
                $capacity = $room['capacity_room'];
                $find = DB::table('room')->where([
                    ['hotel_id', $reserve['hotel_id']], ['capacity_room', $room['capacity_room']]
                ])->first();
                $price_all_reserve = $price_all_reserve +  $find->price_room * $room['number_of_room'];
            }
            $re = [
                "start_reservation" => $reserve['start_reservation'],
                "end_reservation" => $reserve['end_reservation'],
                "tourist_id" => (DB::table('tourist')->where('tourist_name', "nawwar")->first())->id,
                "status" => $reserve['status'],
                "price_all_reserve" => $price_all_reserve
            ];
            $create = reserve::create($re);
            foreach ($reserve['rooms'] as $room) {
                $reserve_has_room = [
                    'reserve_id' => $create->id, 'number' => $room['number_of_room'], 'room_id' => $find->id
                ];
                reserve_has_room::create($reserve_has_room);
            }
        }
    }
}
