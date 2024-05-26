<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\city;
use App\Models\nation;
use Illuminate\Http\Request;

class locationsController extends Controller
{
    public function adminCreateCity(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "city_name" => "required|string",
                "nation_id" => "required|integer"
            ]
        );
        $cityData = [
            "city_name" => $request->city_name,
            "nation_id" => $request->nation_id
        ];
        city::create($cityData);
        return response()->json([
            "status" => 1,
            "message" => "city created"
        ]);
    }
    public function adminCreateNation(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "nation_name" => "required|string"
            ]
        );
        $nationData = [
            "nation_name" => $request->city_name,
        ];
        nation::create($nationData);
        return response()->json([
            "status" => 1,
            "message" => "nation created"
        ]);
    }
}
