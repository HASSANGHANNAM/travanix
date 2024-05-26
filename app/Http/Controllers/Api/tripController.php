<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\trip;
use App\Models\trip_has_place;
use Illuminate\Http\Request;

class tripController extends Controller
{
    public function adminCreateTrip(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "type_of_trip" => "required|max:45",
                "reviews_about_trip" => "required",
                "price_trip" => "required",
                'places.*.place_id' => 'required|integer',
                'places.*.type' => 'required|integer'
            ]
        );
        $tripData = [
            'type_of_trip' => $request->type_of_trip,
            'reviews_about_trip' => $request->reviews_about_trip,
            'price_trip' => $request->price_trip
        ];
        $trip = trip::create($tripData);
        foreach ($request->places as $place) {
            $trip_has_placeData = [
                'place_id' => $place['place_id'], 'type' => $place['type'], 'trip_id' => $trip->id
            ];
            $create_trip_has_place = trip_has_place::create($trip_has_placeData);
        }
        return response()->json([
            "status" => 1,
            "message" => "trip created"
        ]);
    }
}
