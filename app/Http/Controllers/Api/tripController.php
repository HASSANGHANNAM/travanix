<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\resturant;
use App\Models\tourist_details;
use App\Models\tourist_has_trip;
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
                "trip_name" => "required",
                "type_of_trip" => "required|max:45",
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
            'trip_name' => $request->trip_name,
            'price_trip' => $request->price_trip,
            'number_of_allSeat' => $request->number_of_allSeat,
            'trip_start_time' => $request->trip_start_time,
            'trip_end_time' => $request->trip_end_time,
        ];
        $trip = trip::create($tripData);
        foreach ($request->places as $place) {
            if (isset($place['hotel_id'])) {
                $trip_has_placeData = [
                    'hotel_id' => $place['hotel_id'], 'trip_id' => $trip->id
                ];
                $create_trip_has_place = trip_has_place::create($trip_has_placeData);
            }
            if (isset($place['resturant_id'])) {
                $trip_has_placeData = [
                    'resturant_id' => $place['resturant_id'], 'trip_id' => $trip->id
                ];
                $create_trip_has_place = trip_has_place::create($trip_has_placeData);
            }
            if (isset($place['attraction_activity_id'])) {
                $trip_has_placeData = [
                    'attraction_activity_id' => $place['attraction_activity_id'], 'trip_id' => $trip->id
                ];
                $create_trip_has_place = trip_has_place::create($trip_has_placeData);
            }
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
            $sum = tourist_has_trip::where('trip_id', $t->id)->sum('number_of_seat');
            $number_of_seats_available = $t->number_of_allSeat - $sum;
            $data[] = [
                'id' => $t->id,
                'type_of_trip' => $t->type_of_trip,
                'trip_name' => $t->trip_name,
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
                "number_of_seats_available" => $number_of_seats_available
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
        $sum = tourist_has_trip::where('trip_id', $tripData->id)->sum('number_of_seat');
        $number_of_seats_available = $tripData->number_of_allSeat - $sum;
        $data = [
            'id' => $tripData->id,
            'type_of_trip' => $tripData->type_of_trip,
            'trip_name' => $tripData->trip_name,
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
            "number_of_seats_available" => $number_of_seats_available
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
            $sum = tourist_has_trip::where('trip_id', $t->id)->sum('number_of_seat');
            $number_of_seats_available = $t->number_of_allSeat - $sum;
            $data[] = [
                'id' => $t->id,
                'type_of_trip' => $t->type_of_trip,
                'trip_name' => $t->trip_name,
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
                "favorite" => $fav,
                "number_of_seats_available" => $number_of_seats_available

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
        $sum = tourist_has_trip::where('trip_id', $tripData->id)->sum('number_of_seat');
        $number_of_seats_available = $tripData->number_of_allSeat - $sum;
        $data = [
            'id' => $tripData->id,
            'type_of_trip' => $tripData->type_of_trip,
            'trip_name' => $tripData->trip_name,
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
            "favorite" => $fav,
            "number_of_seats_available" => $number_of_seats_available

        ];
        return response()->json([
            "status" => 1,
            "message" => "trip gets",
            "data" => $data
        ]);
    }
    public function touristReserveTrip(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "trip_id" => "required|max:45",
                "number_of_seat" => "required",
                "phone_number" => "required",
                'detalis.*.name' => 'required|max:45',
                'detalis.*.age' => 'required|integer'
            ]
        );
        $count = count($request->detalis);
        if ($count < $request->number_of_seat) {
            return response()->json([
                "status" => 0,
                "message" => "detalis was less than number_of_seat"
            ]);
        }
        if ($count > $request->number_of_seat) {
            return response()->json([
                "status" => 0,
                "message" => "detalis was more than number_of_seat"
            ]);
        }
        $tripData = trip::find($request->trip_id);
        if ($tripData == null) {
            return response()->json([
                "status" => 0,
                "message" => "trip not found",
            ]);
        }
        $sum = tourist_has_trip::where('trip_id', $tripData->id)->sum('number_of_seat');
        $number_of_seats_available = $tripData->number_of_allSeat - $sum;
        if ($request->number_of_seat > $number_of_seats_available) {
            return response()->json([
                "status" => 0,
                "message" => "number of seat not  found",
            ]);
        }
        $touristId = DB::table('tourist')->where('user_id', auth()->user()->id)->first();
        $tourist_has_tripData = [
            "trip_id" => $request->trip_id,
            "tourist_id" => $touristId->id,
            "number_of_seat" => $request->number_of_seat,
            "phone_number" => $request->phone_number
        ];
        $create = tourist_has_trip::create($tourist_has_tripData);
        foreach ($request->detalis as $d) {
            $detalisData = [
                "name" => $d['name'],
                "age" => $d['age'],
                "tourist_has_trip_id" => $create->id
            ];
            tourist_details::create($detalisData);
        }
        return response()->json([
            "status" => 1,
            "message" => "trip Reserved",
        ]);
    }
}
