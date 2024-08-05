<?php

namespace Database\Seeders;

use App\Models\tourist;
use App\Models\tourist_details;
use App\Models\tourist_has_trip;
use App\Models\trip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class tripReservedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reserves = [
            [
                "trip_id" => 1,
                "number_of_seat" => 2,
                "phone_number" => "09345234343",
                "status" => "Pending",
                "detalis" => [
                    [
                        "name" => "hassan",
                        "age" => 21
                    ],
                    [
                        "name" => "omar",
                        "age" => 21
                    ]
                ]
            ],
            [
                "trip_id" => 1,
                "number_of_seat" => 1,
                "phone_number" => "0909434",
                "status" => "Submitted",
                "detalis" => [
                    [
                        "name" => "nawwar",
                        "age" => 21
                    ]
                ]
            ],
            [
                "trip_id" => 1,
                "number_of_seat" => 3,
                "phone_number" => "09343434",
                "status" => "Canceled",
                "detalis" => [
                    [
                        "name" => "hassan",
                        "age" => 21
                    ],
                    [
                        "name" => "omar",
                        "age" => 21
                    ], [
                        "name" => "nawwar",
                        "age" => 21
                    ]
                ]
            ]
        ];
        foreach ($reserves as $reserve) {
            $touristId = DB::table('tourist')->where('tourist_name', "nawwar")->first();
            $tourist_has_tripData = [
                "trip_id" => $reserve['trip_id'],
                "tourist_id" => $touristId->id,
                "number_of_seat" => $reserve['number_of_seat'],
                "phone_number" => $reserve['phone_number'],
                "status" => $reserve['status'],
            ];
            $create = tourist_has_trip::create($tourist_has_tripData);
            foreach ($reserve['detalis'] as $d) {
                $detalisData = [
                    "name" => $d['name'],
                    "age" => $d['age'],
                    "tourist_has_trip_id" => $create->id
                ];
                tourist_details::create($detalisData);
            }
        }
    }
}
