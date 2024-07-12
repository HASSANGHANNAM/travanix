<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\attraction_activity;
use App\Models\comment;
use App\Models\hotel;
use App\Models\rate;
use App\Models\resturant;
use App\Models\trip;
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
            "trip_id" => "integer",
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
        if (isset($request->trip_id)) {
            $chekeFavorite = DB::table('rate')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['trip_id', '=',  $request->trip_id]
                ]
            )->first();
            if ($chekeFavorite == null) {
                $ratePut = [
                    "tourist_id" =>  $touristId->id,
                    "trip_id" => $request->trip_id,
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
                    ['trip_id', '=',  $request->trip_id]
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
                "trip_id" => "integer",
            ]
        );
        $touristId = DB::table('tourist')->where('user_id', auth()->user()->id)->first();
        if (isset($request->resturant_id)) {
            $chekeFavorite = DB::table('rate')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['resturant_id', '=',  $request->resturant_id]
                ]
            )->first();
            if ($chekeFavorite == null) {
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
            $overrideFavorite = DB::table('comment')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['resturant_id', '=',  $request->resturant_id]
                ]
            )->update(array('comment' => $request->comment));
            return response()->json([
                "status" => 1,
                "message" => "succes you override your comment"
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
            $overrideFavorite = DB::table('comment')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['hotel_id', '=',  $request->hotel_id]
                ]
            )->update(array('comment' => $request->comment));
            return response()->json([
                "status" => 1,
                "message" => "succes you override your comment"
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
            $overrideFavorite = DB::table('comment')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['attraction_activity_id', '=',  $request->attraction_activity_id]
                ]
            )->update(array('comment' => $request->comment));
            return response()->json([
                "status" => 1,
                "message" => "succes you override your comment"
            ]);
        }
        if (isset($request->trip_id)) {
            $chekeFavorite = DB::table('comment')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['trip_id', '=',  $request->trip_id]
                ]
            )->first();
            if ($chekeFavorite == null) {
                $putComment = [
                    "tourist_id" => $touristId->id,
                    "trip_id" => $request->trip_id,
                    "comment" => $request->comment,
                ];
                $createcomment = comment::create($putComment);
                return response()->json([
                    "status" => 1,
                    "message" => "succes put your comment"
                ]);
            }
            $overrideFavorite = DB::table('comment')->where(
                [
                    ['tourist_id', '=', $touristId->id],
                    ['trip_id', '=',  $request->trip_id]
                ]
            )->update(array('comment' => $request->comment));
            return response()->json([
                "status" => 1,
                "message" => "succes you override your comment"
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "place id not found"
        ]);
    }
    public function  touristGetAllRateAndComment(Request $request)
    {
        auth()->user();
        $hotelId = $request->input('hotel_id');
        $attractionId = $request->input('attraction_activity_id');
        $restaurantId = $request->input('restaurant_id');
        $tripId = $request->input('trip_id');
        if (isset($hotelId) & !isset($restaurantId) & !isset($tripId) & !isset($attractionId)) {
            if (is_numeric($hotelId)) {
                $hotel = hotel::find($hotelId);
                if ($hotel == null) {
                    return response()->json([
                        "status" => 0,
                        "message" => "hotel not found"
                    ]);
                }
                $commentsWithRates = DB::table('comment')
                    ->join('rate', function ($join) use ($hotelId) {
                        $join->on('comment.tourist_id', '=', 'rate.tourist_id')
                            ->where('comment.hotel_id', '=', $hotelId)
                            ->where('rate.hotel_id', '=', $hotelId);
                    })
                    ->get();
                $data = [];
                foreach ($commentsWithRates as $c) {
                    $touristData = DB::table('tourist')->where('id', $c->tourist_id)->first();
                    $userData = DB::table('users')->where('id', $touristData->user_id)->first();
                    $data[] = [
                        'tourist_id' => $c->tourist_id,
                        'tourist_name' => $touristData->tourist_name,
                        'Email_address' => $userData->Email_address,
                        'hotel_id' => $c->hotel_id,
                        'comment' => $c->comment,
                        'rate' => $c->rate,
                    ];
                }
                return response()->json([
                    "status" => 1,
                    "message" => "succes   ",
                    "data" => $data
                ]);
            }
            return response()->json([
                "status" => 0,
                "message" => "hotel_id must be integer"
            ]);
        }
        if (!isset($hotelId) & isset($restaurantId) & !isset($tripId) & !isset($attractionId)) {
            if (is_numeric($restaurantId)) {
                $resturant = resturant::find($restaurantId);
                if ($resturant == null) {
                    return response()->json([
                        "status" => 0,
                        "message" => "resturant not found"
                    ]);
                }
                $commentsWithRates = DB::table('comment')
                    ->join('rate', function ($join) use ($restaurantId) {
                        $join->on('comment.tourist_id', '=', 'rate.tourist_id')
                            ->where('comment.resturant_id', '=', $restaurantId)
                            ->where('rate.resturant_id', '=', $restaurantId);
                    })
                    ->get();
                $data = [];
                foreach ($commentsWithRates as $c) {
                    $touristData = DB::table('tourist')->where('id', $c->tourist_id)->first();
                    $userData = DB::table('users')->where('id', $touristData->user_id)->first();
                    $data[] = [
                        'tourist_id' => $c->tourist_id,
                        'tourist_name' => $touristData->tourist_name,
                        'Email_address' => $userData->Email_address,
                        'resturant_id' => $c->resturant_id,
                        'comment' => $c->comment,
                        'rate' => $c->rate,
                    ];
                }
                return response()->json([
                    "status" => 1,
                    "message" => "succes   ",
                    "data" => $data
                ]);
            }
            return response()->json([
                "status" => 0,
                "message" => "resturant_id must be integer"
            ]);
        }
        if (!isset($hotelId) & !isset($restaurantId) & isset($tripId) & !isset($attractionId)) {
            if (is_numeric($tripId)) {
                $trip = trip::find($tripId);
                if ($trip == null) {
                    return response()->json([
                        "status" => 0,
                        "message" => "trip not found"
                    ]);
                }
                $commentsWithRates = DB::table('comment')
                    ->join('rate', function ($join) use ($tripId) {
                        $join->on('comment.tourist_id', '=', 'rate.tourist_id')
                            ->where('comment.trip_id', '=', $tripId)
                            ->where('rate.trip_id', '=', $tripId);
                    })
                    ->get();
                $data = [];
                foreach ($commentsWithRates as $c) {
                    $touristData = DB::table('tourist')->where('id', $c->tourist_id)->first();
                    $userData = DB::table('users')->where('id', $touristData->user_id)->first();
                    $data[] = [
                        'tourist_id' => $c->tourist_id,
                        'tourist_name' => $touristData->tourist_name,
                        'Email_address' => $userData->Email_address,
                        'trip_id' => $c->trip_id,
                        'comment' => $c->comment,
                        'rate' => $c->rate,
                    ];
                }
                return response()->json([
                    "status" => 1,
                    "message" => "succes   ",
                    "data" => $data
                ]);
            }
            return response()->json([
                "status" => 0,
                "message" => "trip_id must be integer"
            ]);
        }
        if (!isset($hotelId) & !isset($restaurantId) & !isset($tripId) & isset($attractionId)) {
            if (is_numeric($attractionId)) {
                $attraction_activity = attraction_activity::find($attractionId);
                if ($attraction_activity == null) {
                    return response()->json([
                        "status" => 0,
                        "message" => "attraction_activity not found"
                    ]);
                }
                $commentsWithRates = DB::table('comment')
                    ->join('rate', function ($join) use ($attractionId) {
                        $join->on('comment.tourist_id', '=', 'rate.tourist_id')
                            ->where('comment.attraction_activity_id', '=', $attractionId)
                            ->where('rate.attraction_activities_id', '=', $attractionId);
                    })
                    ->get();
                $data = [];
                foreach ($commentsWithRates as $c) {
                    $touristData = DB::table('tourist')->where('id', $c->tourist_id)->first();
                    $userData = DB::table('users')->where('id', $touristData->user_id)->first();
                    $data[] = [
                        'tourist_id' => $c->tourist_id,
                        'tourist_name' => $touristData->tourist_name,
                        'Email_address' => $userData->Email_address,
                        'attraction_activity_id' => $c->attraction_activity_id,
                        'comment' => $c->comment,
                        'rate' => $c->rate,
                    ];
                }
                return response()->json([
                    "status" => 1,
                    "message" => "succes   ",
                    "data" => $data
                ]);
            }
            return response()->json([
                "status" => 0,
                "message" => "attraction_activity_id must be integer"
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "you send more than parameter or not send it"
        ]);
    }
}
