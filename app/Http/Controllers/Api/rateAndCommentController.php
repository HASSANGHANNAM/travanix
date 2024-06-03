<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\comment;
use App\Models\rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class rateAndCommentController extends Controller
{
    public function  touristPutRate(Request $request)
    {
        auth()->user();
        $favoriteData = $request->validate([
            "resturant_id" => "integer",
            "hotel_id" => "integer",
            "attraction_activity_id" => "integer",
            "rate" => "required"
        ]);
        $touristId = DB::table('tourist')->where('user_id', auth()->user()->id)->first();
        if (isset($request->resturant_id)) {

            $chekeFavorite = DB::table('rate')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['resturant_id', '=',  $request->resturant_id]
                ]
            )->first();
            if ($chekeFavorite == null) {
                $ratePut = [
                    "tourist_id" =>  $touristId->id,
                    "resturant_id" => $request->resturant_id,
                    "rate" => $request->rate
                ];
                $putRate = rate::create($ratePut);
                return response()->json([
                    "status" => 1,
                    "message" => "succes put your rate"
                ]);
            }
            $overrideFavorite = DB::table('rate')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['resturant_id', '=',  $request->resturant_id]
                ]
            )->update(array('rate' => $request->rate));
            return response()->json([
                "status" => 1,
                "message" => "succes you override your rate"
            ]);
        }
        if (isset($request->hotel_id)) {
            $chekeFavorite = DB::table('rate')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['hotel_id', '=',  $request->hotel_id]
                ]
            )->first();
            if ($chekeFavorite == null) {
                $ratePut = [
                    "tourist_id" =>  $touristId->id,
                    "hotel_id" => $request->hotel_id,
                    "rate" => $request->rate
                ];
                $putRate = rate::create($ratePut);
                return response()->json([
                    "status" => 1,
                    "message" => "succes put your rate"
                ]);
            }
            $overrideFavorite = DB::table('rate')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['hotel_id', '=',  $request->hotel_id]
                ]
            )->update(array('rate' => $request->rate));
            return response()->json([
                "status" => 1,
                "message" => "succes you override your rate"
            ]);
        }
        if (isset($request->attraction_activity_id)) {
            $chekeFavorite = DB::table('rate')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['attraction_activity_id', '=',  $request->attraction_activity_id]
                ]
            )->first();
            if ($chekeFavorite == null) {
                $ratePut = [
                    "tourist_id" =>  $touristId->id,
                    "attraction_activity_id" => $request->attraction_activity_id,
                    "rate" => $request->rate
                ];
                $putRate = rate::create($ratePut);
                return response()->json([
                    "status" => 1,
                    "message" => "succes put your rate"
                ]);
            }
            $overrideFavorite = DB::table('rate')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['attraction_activity_id', '=',  $request->attraction_activity_id]
                ]
            )->update(array('rate' => $request->rate));
            return response()->json([
                "status" => 1,
                "message" => "succes you override your rate"
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "i want place id"
        ]);
    }
    public function  touristPutComment(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "comment" => "required|string",
                "resturant_id" => "integer",
                "hotel_id" => "integer",
                "attraction_activity_id" => "integer",
            ]
        );
        $touristId = DB::table('tourist')->where('user_id', auth()->user()->id)->first();
        if (isset($request->resturant_id)) {
            $putComment = [
                "tourist_id" => $touristId->id,
                "resturant_id" => $request->resturant_id,
                "comment" => $request->comment,
            ];
            $createcomment = comment::create($putComment);
            return response()->json([
                "status" => 1,
                "message" => "succes put your comment"
            ]);
        }
        if (isset($request->hotel_id)) {
            $putComment = [
                "tourist_id" => $touristId->id,
                "hotel_id" => $request->hotel_id,
                "comment" => $request->comment,
            ];
            $createcomment = comment::create($putComment);
            return response()->json([
                "status" => 1,
                "message" => "succes put your comment"
            ]);
        }
        if (isset($request->attraction_activity_id)) {
            $putComment = [
                "tourist_id" => $touristId->id,
                "attraction_activity_id" => $request->attraction_activity_id,
                "comment" => $request->comment,
            ];
            $createcomment = comment::create($putComment);
            return response()->json([
                "status" => 1,
                "message" => "succes put your comment"
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "place id not found"
        ]);
    }
}
