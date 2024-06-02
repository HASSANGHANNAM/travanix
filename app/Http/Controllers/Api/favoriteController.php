<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\favorite;
use App\Models\tourist;
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
    }
}
