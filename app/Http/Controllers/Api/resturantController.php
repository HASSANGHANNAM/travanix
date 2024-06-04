<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\city;
use App\Models\image;
use App\Models\image_resturant;
use App\Models\location;
use App\Models\nation;
use App\Models\resturant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class resturantController extends Controller
{
    public function adminCreateResturant(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "resturant_name" => "required|max:45|unique:resturant",
                "type_of_food" => "required|max:255",
                "address" => "required|max:255",
                "city_id" => "required",
                "coordinate_x" => "required",
                "coordinate_y" => "required",
                "phone_number" => "required",
                "descreption" => "required",
                "opining_time" => "required",
                "closing_time" => "required",
                "resturant_class" => "required",
                "images" => "required|array"
            ]
        );
        $locationData = [
            'city_id' => $request->city_id,
            'address' => $request->address,
            'coordinate_x' => $request->coordinate_x,
            'coordinate_y' => $request->coordinate_y
        ];
        $location = location::create($locationData);
        $resturantData = [
            "resturant_name" => $request->resturant_name,
            'type_of_food' => $request->type_of_food,
            'resturant_class' => $request->resturant_class,
            'descreption' => $request->descreption,
            'phone_number' => $request->phone_number,
            'opining_time' => $request->opining_time,
            'closing_time' => $request->closing_time,
            'location_id' => $location->id,
        ];
        $createResturant = resturant::create($resturantData);
        $counter = 1;
        foreach ($request->images as $image) {
            $imagePut = base64_decode($image);
            $extension = ".jpg";
            $filename = $resturantData['resturant_name'] . $counter . $extension;
            $imagePath = public_path('images/resturants/' . $filename);
            $pathToStore = "/images/resturants/" . $filename;
            file_put_contents($imagePath, $imagePut);
            $counter++;
            $imageData = [
                'path_of_image' => $pathToStore, 'resturant_id' => $createResturant->id
            ];
            $creatImage = image::create($imageData);
        }
        return response()->json([
            "status" => 1,
            "message" => "resturant created"
        ]);
    }
    public function adminGetResturants()
    {
        auth()->user();
        $resturantData = resturant::with('images')->get();
        $data = [];
        foreach ($resturantData as $r) {
            $location = location::find($r->location_id);
            $city = city::find($location->city_id);
            $nation = nation::find($city->nation_id);
            $data[] = [
                'id' => $r->id,
                'address' => $location->address,
                'coordinate_y' => $location->coordinate_y,
                'coordinate_x' => $location->coordinate_x,
                'city_name' => $city->city_name,
                'nation_name' => $nation->nation_name,
                'type_of_food' => $r->type_of_food,
                'descreption' => $r->descreption,
                'resturant_name' => $r->resturant_name,
                'resturant_class' => $r->resturant_class,
                'phone_number' => $r->phone_number,
                'opining_time' => $r->opining_time,
                'closing_time' => $r->closing_time,
                'images' => $r->images->map(function ($image) {
                    return $image->path_of_image;
                })->all()
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "resturants gets",
            "data" => $data
        ]);
    }
    //TODO:
    public function adminDeleteResturant($id)
    {
    }
    //TODO:
    public function adminUpdateResturant()
    {
    }
    public function adminGetResturantById($id)
    {
        auth()->user();
        $resturantData = resturant::with('images')->find($id);
        if ($resturantData == null) {
            return response()->json([
                "status" => 0,
                "message" => "resturant not found",
            ]);
        }
        $location = location::find($resturantData->location_id);
        $city = city::find($location->city_id);
        $nation = nation::find($city->nation_id);
        $data[] = [
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
            })->all()
        ];
        return response()->json([
            "status" => 1,
            "message" => "resturant get",
            "data" => $data
        ]);
    }
    public function touristGetResturantById($id)
    {
        auth()->user();
        $resturantData = resturant::with('images')->find($id);
        if ($resturantData == null) {
            return response()->json([
                "status" => 0,
                "message" => "resturant not found",
            ]);
        }
        $location = location::find($resturantData->location_id);
        $city = city::find($location->city_id);
        $nation = nation::find($city->nation_id);
        $fav = false;
        if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['resturant_id', $id]])->first()) {
            $fav = true;
        }
        $data[] = [
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
        return response()->json([
            "status" => 1,
            "message" => "resturant get",
            "data" => $data
        ]);
    }
    public function touristGetResturants()
    {
        auth()->user();
        $resturantData = resturant::with('images')->get();
        $data = [];
        foreach ($resturantData as $r) {
            $location = location::find($r->location_id);
            $city = city::find($location->city_id);
            $nation = nation::find($city->nation_id);
            $fav = false;
            if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['resturant_id', $r->id]])->first()) {
                $fav = true;
            }
            $data[] = [
                'id' => $r->id,
                'address' => $location->address,
                'coordinate_y' => $location->coordinate_y,
                'coordinate_x' => $location->coordinate_x,
                'city_name' => $city->city_name,
                'nation_name' => $nation->nation_name,
                'type_of_food' => $r->type_of_food,
                'descreption' => $r->descreption,
                'resturant_name' => $r->resturant_name,
                'resturant_class' => $r->resturant_class,
                'phone_number' => $r->phone_number,
                'opining_time' => $r->opining_time,
                'closing_time' => $r->closing_time,
                'images' => $r->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "favorite" => $fav
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "resturants gets",
            "data" => $data
        ]);
    }
}
