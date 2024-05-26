<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\image_resturant;
use App\Models\location;
use App\Models\resturant;
use Illuminate\Http\Request;

class resturantController extends Controller
{
    public function adminCreateResturant(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "resturant_name" => "required|max:45",
                "type_of_food" => "required|max:255",
                "address" => "required|max:255",
                "city_id" => "required",
                "coordinate_x" => "required",
                "coordinate_y" => "required",
                "images" => "required|array"
            ]
        );
        $locationData = [
            'city_id' => $request->city_id,
            'address' => $request->address,
            'coordinate_x' => $request->city_id,
            'coordinate_y' => $request->city_id
        ];
        $location = location::create($locationData);
        $resturantData = [
            "resturant_name" => $request->resturant_name,
            'type_of_food' => $request->type_of_food,
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
            $creatImage = image_resturant::create($imageData);
        }
        return response()->json([
            "status" => 1,
            "message" => "resturant created"
        ]);
    }
}
