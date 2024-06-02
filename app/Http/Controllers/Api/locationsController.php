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
        if (auth()->user()->type == 2) {
            return response()->json([
                "status" => 0,
                "message" => "you are not admin"
            ]);
        }
        $request->validate(
            [
                "city_name" => "required|string",
                "nation_id" => "required|inteDeleter"
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
    public function adminUpdateCity(Request $request)
    {
        auth()->user();
        if (auth()->user()->type == 2) {
            return response()->json([
                "status" => 0,
                "message" => "you are not admin"
            ]);
        }
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
    public function  adminCreateNation(Request $request)
    {
        auth()->user();
        if (auth()->user()->type == 2) {
            return response()->json([
                "status" => 0,
                "message" => "you are not admin"
            ]);
        }
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
    public function adminUpdateNation(Request $request)
    {
        auth()->user();
        if (auth()->user()->type == 2) {
            return response()->json([
                "status" => 0,
                "message" => "you are not admin"
            ]);
        }
        $request->validate([
            "id" => "required|integer",
            "nation_name" => "required|max:45|string"
        ]);
        $nationData = DB::table('nation')->where('id', $request->id)->select('id', 'nation_name')->first();
        if ($nationData == null) {
            return response()->json([
                "status" => 0,
                "message" => "nation not found "
            ]);
        }
        if ($nationData->nation_name == $request->nation_name) {
            return response()->json([
                "status" => 1,
                "message" => "succes"
            ]);
        }
        $request->validate([
            "nation_name" => "unique:nation"
        ]);
        $update = nation::where('id', $request->id)->update(array('nation_name' => $request->nation_name));
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
    public function adminGetCitiesByNation($nation_id)
    {
        auth()->user();
        if (auth()->user()->type == 2) {
            return response()->json([
                "status" => 0,
                "message" => "you are not admin"
            ]);
        }
        if (!isset($nation_id)) {
            return response()->json([
                "status" => 0,
                "message" => "id is required"
            ]);
        }
        $cities = DB::table('city')->where('nation_id', $nation_id)->get();
        return response()->json([
            "status" => 1,
            "message" => "cities in nation ",
            "data" => $cities
        ]);
    }
    public function adminGetAllCities()
    {
        auth()->user();
        if (auth()->user()->type == 2) {
            return response()->json([
                "status" => 0,
                "message" => "you are not admin"
            ]);
        }
        $cities = DB::table('city')->select('id', 'city_name', 'nation_id')->get();
        return response()->json([
            "status" => 1,
            "message" => "cities in nation ",
            "data" => $cities
        ]);
    }
    public function adminDeleteCity($id)
    {
        auth()->user();
        if (auth()->user()->type == 2) {
            return response()->json([
                "status" => 0,
                "message" => "you are not admin"
            ]);
        }
        if (!isset($id)) {
            return response()->json([
                "status" => 0,
                "message" => "id is required"
            ]);
        }
        $cities = DB::table('city')->where('id', $id)->delete();
        // dd($cities);
        if ($cities == 0) {
            return response()->json([
                "status" => 0,
                "message" => "city not found"
            ]);
        }
        return response()->json([
            "status" => 1,
            "message" => "city was deleted"
        ]);
    }
    public function adminDeleteNation($id)
    {
        auth()->user();
        if (auth()->user()->type == 2) {
            return response()->json([
                "status" => 0,
                "message" => "you are not admin"
            ]);
        }
        if (!isset($id)) {
            return response()->json([
                "status" => 0,
                "message" => "id is required"
            ]);
        }
        $nation = DB::table('nation')->where('id', $id)->first();
        if ($nation == null) {
            return response()->json([
                "status" => 0,
                "message" => "nation not found"
            ]);
        }
        DB::table('city')->where('nation_id', $id)->delete();
        DB::table('nation')->where('id', $id)->delete();
        return response()->json([
            "status" => 1,
            "message" => "nation and her cities were deleted"
        ]);
    }
    public function adminGetAllNations()
    {
        auth()->user();
        if (auth()->user()->type == 2) {
            return response()->json([
                "status" => 0,
                "message" => "you are not admin"
            ]);
        }
        $nations = DB::table('nation')->select('id', 'nation_name')->get();
        return response()->json([
            "status" => 1,
            "message" => " nations",
            "data" => $nations
        ]);
    }
    public function touristGetCitiesByNation($nation_id)
    {
        auth()->user();
        if (auth()->user()->type == 1) {
            return response()->json([
                "status" => 0,
                "message" => "you are not tourist"
            ]);
        }
        if (!isset($nation_id)) {
            return response()->json([
                "status" => 0,
                "message" => "id is required"
            ]);
        }
        $cities = DB::table('city')->where('nation_id', $nation_id)->get();
        return response()->json([
            "status" => 1,
            "message" => "cities in nation ",
            "data" => $cities
        ]);
    }
    public function touristGetAllCities()
    {
        auth()->user();
        if (auth()->user()->type == 1) {
            return response()->json([
                "status" => 0,
                "message" => "you are not tourist"
            ]);
        }
        $cities = DB::table('city')->select('id', 'city_name', 'nation_id')->get();
        return response()->json([
            "status" => 1,
            "message" => "cities in nation ",
            "data" => $cities
        ]);
    }
    public function touristGetAllNations()
    {
        auth()->user();
        if (auth()->user()->type == 1) {
            return response()->json([
                "status" => 0,
                "message" => "you are not tourist"
            ]);
        }
        $nations = DB::table('nation')->select('id', 'nation_name')->get();
        return response()->json([
            "status" => 1,
            "message" => " nations",
            "data" => $nations
        ]);
    }
}
