<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\city;
use App\Models\nation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function  adminCreateNation(Request $request)
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
    public function adminUpdateCity(Request $request)
    {
        $request->validate([
            "id" => "required|integer",
            "nation_id" => "required|integer",
            "city_name" => "required|max:45|string"
        ]);
        $cityData = DB::table('city')->where('id', $request->id)->select('id', 'city_name', 'nation_id')->first();
        if ($cityData == null) {
            return response()->json([
                "status" => 0,
                "message" => "city not found "
            ]);
        }
        $nationId = nation::find($request->nation_id);
        if ($nationId == null) {
            return response()->json([
                "status" => 0,
                "message" => "nation not found "
            ]);
        }
        if ($cityData->city_name == $request->city_name && $cityData->nation_id == $request->nation_id) {
            return response()->json([
                "status" => 1,
                "message" => "succes"
            ]);
        }
        if ($cityData->city_name == $request->city_name) {
            $update = city::where('id', $request->id)->update(array('nation_id' => $nationId['id']));
            if ($update != 0) {
                return response()->json([
                    "status" => 1,
                    "message" => "succes"
                ]);
            }
            return response()->json([
                "status" => 0,
                "message" => "not succes"
            ]);
        }
        $request->validate([
            "city_name" => "unique:city"
        ]);
        $update = city::where('id', $request->id)->update(array('city_name' => $request->city_name, 'nation_id' => $nationId['id']));
        if ($update != 0) {
            return response()->json([
                "status" => 1,
                "message" => "succes"
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "not succes"
        ]);
    }
}
//TODO:
// unsigned
// $table->enum("status", ["bending", "acceptable", "unacceptable"])->nullable();
// ->unique()