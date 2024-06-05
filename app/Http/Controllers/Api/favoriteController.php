<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\attraction_activity;
use App\Models\city;
use App\Models\favorite;
use App\Models\hotel;
use App\Models\location;
use App\Models\nation;
use App\Models\resturant;
use App\Models\service;
use App\Models\tourist;
use App\Models\tourist_has_trip;
use App\Models\trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class favoriteController extends Controller
{
    public function  touristPutDeleteFavorite(Request $request)
    {
        auth()->user();
        $favoriteData = $request->validate([
            "resturant_id" => "integer",
            "hotel_id" => "integer",
            "attraction_activity_id" => "integer",
            "trip_id" => "integer",
        ]);
        $touristId = DB::table('tourist')->where('user_id', auth()->user()->id)->first();
        if (isset($request->resturant_id)) {
            $chekeFavorite = DB::table('favorite')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['resturant_id', '=',  $request->resturant_id]
                ]
            )->first();
            if ($chekeFavorite == null) {
                $favoritePut = [
                    "tourist_id" =>  $touristId->id,
                    "resturant_id" => $request->resturant_id
                ];
                $putFavorite = favorite::create($favoritePut);
                return response()->json([
                    "status" => 1,
                    "message" => "succes put in favorite"
                ]);
            }
            $deleteFavorite = DB::table('favorite')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['resturant_id', '=',  $request->resturant_id]
                ]
            )->delete();
            return response()->json([
                "status" => 1,
                "message" => "succes delete from favorite"
            ]);
        }
        if (isset($request->hotel_id)) {
            $chekeFavorite = DB::table('favorite')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['hotel_id', '=',  $request->hotel_id]
                ]
            )->first();
            if ($chekeFavorite == null) {
                $favoritePut = [
                    "tourist_id" =>  $touristId->id,
                    "hotel_id" => $request->hotel_id
                ];
                $putFavorite = favorite::create($favoritePut);
                return response()->json([
                    "status" => 1,
                    "message" => "succes put in favorite"
                ]);
            }
            $deleteFavorite = DB::table('favorite')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['hotel_id', '=',  $request->hotel_id]
                ]
            )->delete();
            return response()->json([
                "status" => 1,
                "message" => "succes delete from favorite"
            ]);
        }
        if (isset($request->attraction_activity_id)) {
            $chekeFavorite = DB::table('favorite')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['attraction_activity_id', '=',  $request->attraction_activity_id]
                ]
            )->first();
            if ($chekeFavorite == null) {
                $favoritePut = [
                    "tourist_id" =>  $touristId->id,
                    "attraction_activity_id" => $request->attraction_activity_id
                ];
                $putFavorite = favorite::create($favoritePut);
                return response()->json([
                    "status" => 1,
                    "message" => "succes put in favorite"
                ]);
            }
            $deleteFavorite = DB::table('favorite')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['attraction_activity_id', '=',  $request->attraction_activity_id]
                ]
            )->delete();
            return response()->json([
                "status" => 1,
                "message" => "succes delete from favorite"
            ]);
        }
        if (isset($request->trip_id)) {
            $chekeFavorite = DB::table('favorite')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['trip_id', '=',  $request->trip_id]
                ]
            )->first();
            if ($chekeFavorite == null) {
                $favoritePut = [
                    "tourist_id" =>  $touristId->id,
                    "trip_id" => $request->trip_id
                ];
                $putFavorite = favorite::create($favoritePut);
                return response()->json([
                    "status" => 1,
                    "message" => "succes put in favorite"
                ]);
            }
            $deleteFavorite = DB::table('favorite')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['trip_id', '=',  $request->trip_id]
                ]
            )->delete();
            return response()->json([
                "status" => 1,
                "message" => "succes delete from favorite"
            ]);
        }
    }
    public function touristGetAllFavorite()
    {
        auth()->user();
        $touristId = DB::table('tourist')->where('user_id', auth()->user()->id)->first();
        $attraction_activities = [];
        $trips = [];
        $hotels = [];
        $resturants = [];
        $hotelFavorite = DB::table('favorite')->where(
            [
                ['tourist_id', '=', $touristId->id],
                ['hotel_id', '!=',  null]
            ]
        )->select('hotel_id')->get();
        foreach ($hotelFavorite as $hf) {
            $hotelData = hotel::with('images')->find($hf->hotel_id);
            $location = location::find($hotelData->location_id);
            $city = city::find($location->city_id);
            $nation = nation::find($city->nation_id);
            $servicesId = DB::table('hotel_has_services')->where('hotel_id', $hotelData->id)->get();
            $services = [];
            foreach ($servicesId as $ser) {
                $services[] = service::find($ser->service_id)->service;
            }
            $fav = true;
            $hotels[] = [
                'id' => $hotelData->id,
                'address' => $location->address,
                'coordinate_y' => $location->coordinate_y,
                'coordinate_x' => $location->coordinate_x,
                'city_name' => $city->city_name,
                'nation_name' => $nation->nation_name,
                'simple_description_about_hotel' => $hotelData->simple_description_about_hotel,
                'hotel_name' => $hotelData->hotel_name,
                'hotel_class' => $hotelData->hotel_class,
                'phone_number' => $hotelData->phone_number,
                'images' => $hotelData->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "services" => $services,
                "favorite" => $fav
            ];
        }
        $resturantFavorite = DB::table('favorite')->where(
            [
                ['tourist_id', '=', $touristId->id],
                ['resturant_id', '!=',  null]
            ]
        )->select('resturant_id')->get();
        foreach ($resturantFavorite as $rf) {
            $resturantData = resturant::with('images')->find($rf->resturant_id);
            $location = location::find($resturantData->location_id);
            $city = city::find($location->city_id);
            $nation = nation::find($city->nation_id);
            $fav = true;
            $resturants[] = [
                'id' => $resturantData->id,
                'address' => $location->address,
                'coordinate_y' => $location->coordinate_y,
                'coordinate_x' => $location->coordinate_x,
                'city_name' => $city->city_name,
                'nation_name' => $nation->nation_name,
                'type_of_food' => $resturantData->type_of_food,
                'descreption' => $resturantData->descreption,
                'resturant_name' => $resturantData->resturant_name,
                'resturant_class' => $resturantData->resturant_class,
                'phone_number' => $resturantData->phone_number,
                'opining_time' => $resturantData->opining_time,
                'closing_time' => $resturantData->closing_time,
                'images' => $resturantData->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "favorite" => $fav
            ];
        }
        $attraction_activityFavorite = DB::table('favorite')->where(
            [
                ['tourist_id', '=', $touristId->id],
                ['attraction_activity_id', '!=',  null]
            ]
        )->select('attraction_activity_id')->get();
        foreach ($attraction_activityFavorite as $af) {
            $attraction_activityData = attraction_activity::with('images')->find($af->attraction_activity_id);
            $location = location::find($attraction_activityData->location_id);
            $city = city::find($location->city_id);
            $nation = nation::find($city->nation_id);
            $fav = true;
            $attraction_activities[] = [
                'id' => $attraction_activityData->id,
                'address' => $location->address,
                'coordinate_y' => $location->coordinate_y,
                'coordinate_x' => $location->coordinate_x,
                'city_name' => $city->city_name,
                'nation_name' => $nation->nation_name,
                'attraction_activity_name' => $attraction_activityData->attraction_activity_name,
                'description' => $attraction_activityData->description,
                'opening_time' => $attraction_activityData->opening_time,
                'closing_time' => $attraction_activityData->closing_time,
                'images' => $attraction_activityData->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "favorite" => $fav
            ];
        }
        $tripFavorite = DB::table('favorite')->where(
            [
                ['tourist_id', '=', $touristId->id],
                ['trip_id', '!=',  null]
            ]
        )->select('trip_id')->get();
        foreach ($tripFavorite as $tf) {
            $tripData = trip::with('places')->find($tf->trip_id);
            $fav = true;
            $sum = tourist_has_trip::where('trip_id', $tripData->id)->sum('number_of_seat');
            $number_of_seats_available = $tripData->number_of_allSeat - $sum;
            $trips = [
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
        }
        return response()->json([
            "status" => 1,
            "message" => "succes get  favorites",
            "trips" => $trips,
            "hotels" => $hotels,
            "resturants" => $resturants,
            "attraction_activities" => $attraction_activities
        ]);
    }
}
