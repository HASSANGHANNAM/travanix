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
            "type" => "required|integer",
            "favorite_id" => "required|integer",
        ]);
        $touristId = DB::table('tourist')->where('user_id', auth()->user()->id)->first();
        $chekeFavorite = DB::table('favorite')->where(
            [
                ['tourist_id', '=', $touristId->id],
                ['type', '=',  $request->type],
                ['favorite_id', '=',  $request->favorite_id]
            ]
        )->first();
        if ($chekeFavorite == null) {
            $favoritePut = [
                "tourist_id" =>  $touristId->id,
                "type" => $request->type,
                "favorite_id" => $request->favorite_id
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
                ['type', '=',  $request->type],
                ['favorite_id', '=',  $request->favorite_id]
            ]
        )->delete();
        return response()->json([
            "status" => 1,
            "message" => "succes delete from favorite"
        ]);
    }
    public function touristGetAllFavorite()
    {
        auth()->user();
    }
}
