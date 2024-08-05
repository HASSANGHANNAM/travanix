<?php

namespace Database\Seeders;

use App\Models\avg_rate;
use App\Models\location;
use App\Models\trip;
use App\Models\trip_has_place;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class tripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trips = [
            [
                "trip_name" => "Trip To Jordan",
                "description" => "With its desert landscapes, renowned historical and religious sites, and friendly people, Jordan is the perfect introduction to the Middle East. The famous temples of Petra might be the big draw – especially for those of us who grew up watching Indiana Jones and the Last Crusade – but there’s plenty more to see around the rest of the country too.Jordan is a great place for a road trip, with manageable distances, decent roads and cheap fuel meaning you can easily see the best of Jordan in a week. This 7-day Jordan itinerary takes you across the country, with what to see, do and where to stay along the way.",
                "type_of_trip" => "Family",
                "price_trip" => 150,
                "number_of_allSeat" => 50,
                "trip_start_time" => "2024-09-03 08:00:00",
                "trip_end_time" => "2024-09-06 08:00:00",
                "city_id" => 21,
                "address" => "jordan",
                "coordinate_x" => 31.279862,
                "coordinate_y" => 37.1297454,
                "places" => [["hotel_id" => 3], ["resturant_id" => 1], ["attraction_activity_id" => 1]]
            ]
        ];
        foreach ($trips as $trip) {
            $locationData = [
                'city_id' => $trip['city_id'],
                'address' => $trip['address'],
                'coordinate_x' => $trip['coordinate_x'],
                'coordinate_y' => $trip['coordinate_y']
            ];
            $location = location::create($locationData);
            $tripData = [
                'type_of_trip' => $trip['type_of_trip'],
                'trip_name' => $trip['trip_name'],
                'description' => $trip['description'],
                'price_trip' => $trip['price_trip'],
                'number_of_allSeat' => $trip['number_of_allSeat'],
                'trip_start_time' => $trip['trip_start_time'],
                'trip_end_time' => $trip['trip_end_time'],
                'location_id' => $location['id'],
            ];
            $t = trip::create($tripData);
            foreach ($trip['places'] as $place) {
                if (isset($place['hotel_id'])) {
                    $trip_has_placeData = [
                        'hotel_id' => $place['hotel_id'], 'trip_id' => $t->id
                    ];
                    $create_trip_has_place = trip_has_place::create($trip_has_placeData);
                }
                if (isset($place['resturant_id'])) {
                    $trip_has_placeData = [
                        'resturant_id' => $place['resturant_id'], 'trip_id' => $t->id
                    ];
                    $create_trip_has_place = trip_has_place::create($trip_has_placeData);
                }
                if (isset($place['attraction_activity_id'])) {
                    $trip_has_placeData = [
                        'attraction_activity_id' => $place['attraction_activity_id'], 'trip_id' => $t->id
                    ];
                    $create_trip_has_place = trip_has_place::create($trip_has_placeData);
                }
            }
            $rateData = [
                'trip_id' => $t->id,
                'count' => 0,
                'avg' => 0
            ];
            $rate = avg_rate::create($rateData);
        }
    }
}
