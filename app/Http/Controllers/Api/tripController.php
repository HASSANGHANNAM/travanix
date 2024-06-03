<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\resturant;
use App\Models\trip;
use App\Models\trip_has_place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                "number_of_allSeat" => "required|integer",
                "trip_start_time" => "required",
                "trip_end_time" => "required",
                'places.*.hotel_id' => 'integer',
                'places.*.resturant_id' => 'integer',
                'places.*.attraction_activity_id' => 'integer',
            ]
        );
        $tripData = [
            'type_of_trip' => $request->type_of_trip,
            'reviews_about_trip' => $request->reviews_about_trip,
            'price_trip' => $request->price_trip,
            'number_of_allSeat' => $request->number_of_allSeat,
            'trip_start_time' => $request->trip_start_time,
            'trip_end_time' => $request->trip_end_time,
        ];
        $trip = trip::create($tripData);
        // FIXME: place id
        foreach ($request->places as $place) {
            $trip_has_placeData = [
                'hotel_id' => $place['hotel_id'], 'trip_id' => $trip->id
            ];
            $create_trip_has_place = trip_has_place::create($trip_has_placeData);
        }
        return response()->json([
            "status" => 1,
            "message" => "trip created"
        ]);
    }
    public function adminGetTrips()
    {
        auth()->user();
        $tripData = trip::with('places')->get();
        $data = [];
        foreach ($tripData as $t) {
            $data[] = [
                'id' => $t->id,
                'type_of_trip' => $t->type_of_trip,
                'reviews_about_trip' => $t->reviews_about_trip,
                'price_trip' => $t->price_trip,
                'number_of_allSeat' => $t->number_of_allSeat,
                'trip_start_time' => $t->trip_start_time,
                'trip_end_time' => $t->trip_end_time,
                'places' => $t->places->map(function ($place) {
                    if (isset($place->hotel_id))
                        return ['hotel_id' => $place->hotel_id];
                    if (isset($place->attraction_activity_id))
                        return ['attraction_activity_id' => $place->attraction_activity_id];
                    if (isset($place->resturant_id))
                        return ['resturant_id' => $place->resturant_id];
                })->all()
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "trip gets",
            "data" => $data
        ]);
    }
    public function adminDeleteTrip($id)
    {
    }
    public function adminUpdateTrip()
    {
    }
    public function adminGetTripById($id)
    {
        auth()->user();
        $tripData = trip::with('places')->find($id);
        if ($tripData == null) {
            return response()->json([
                "status" => 0,
                "message" => "trip not found",
            ]);
        }
        $data = [
            'id' => $tripData->id,
            'type_of_trip' => $tripData->type_of_trip,
            'reviews_about_trip' => $tripData->reviews_about_trip,
            'price_trip' => $tripData->price_trip,
            'number_of_allSeat' => $tripData->number_of_allSeat,
            'trip_start_time' => $tripData->trip_start_time,
            'trip_end_time' => $tripData->trip_end_time,
            'places' => $tripData->places->map(function ($place) {
                if (isset($place->hotel_id))
                    return ['hotel_id' => $place->hotel_id];
                if (isset($place->attraction_activity_id))
                    return ['attraction_activity_id' => $place->attraction_activity_id];
                if (isset($place->resturant_id))
                    return ['resturant_id' => $place->resturant_id];
            })->all()
        ];
        return response()->json([
            "status" => 1,
            "message" => "trip gets",
            "data" => $data
        ]);
    }
    public function touristGetTrips()
    {
        auth()->user();
        $tripData = trip::with('places')->get();
        $data = [];
        foreach ($tripData as $t) {
            $fav = false;
            if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['trip_id', $t->id]])->first()) {
                $fav = true;
            }
            $data[] = [
                'id' => $t->id,
                'type_of_trip' => $t->type_of_trip,
                'reviews_about_trip' => $t->reviews_about_trip,
                'price_trip' => $t->price_trip,
                'number_of_allSeat' => $t->number_of_allSeat,
                'trip_start_time' => $t->trip_start_time,
                'trip_end_time' => $t->trip_end_time,
                'places' => $t->places->map(function ($place) {
                    if (isset($place->hotel_id))
                        return ['hotel_id' => $place->hotel_id];
                    if (isset($place->attraction_activity_id))
                        return ['attraction_activity_id' => $place->attraction_activity_id];
                    if (isset($place->resturant_id))
                        return ['resturant_id' => $place->resturant_id];
                })->all(),
                "favorite" => $fav

            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "trip gets",
            "data" => $data
        ]);
    }
    public function touristGetTripById($id)
    {
        auth()->user();
        $tripData = trip::with('places')->find($id);
        if ($tripData == null) {
            return response()->json([
                "status" => 0,
                "message" => "trip not found",
            ]);
        }
        $fav = false;
        if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['trip_id', $tripData->id]])->first()) {
            $fav = true;
        }
        $data = [
            'id' => $tripData->id,
            'type_of_trip' => $tripData->type_of_trip,
            'reviews_about_trip' => $tripData->reviews_about_trip,
            'price_trip' => $tripData->price_trip,
            'number_of_allSeat' => $tripData->number_of_allSeat,
            'trip_start_time' => $tripData->trip_start_time,
            'trip_end_time' => $tripData->trip_end_time,
            'places' => $tripData->places->map(function ($place) {
                if (isset($place->hotel_id))
                    return ['hotel_id' => $place->hotel_id];
                if (isset($place->attraction_activity_id))
                    return ['attraction_activity_id' => $place->attraction_activity_id];
                if (isset($place->resturant_id))
                    return ['resturant_id' => $place->resturant_id];
            })->all(),
            "favorite" => $fav
        ];
        return response()->json([
            "status" => 1,
            "message" => "trip gets",
            "data" => $data
        ]);
    }
}
