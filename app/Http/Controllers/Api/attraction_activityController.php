<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\attraction_activity;
use App\Models\image_attraction_activities;
use App\Models\location;
use Illuminate\Http\Request;

class attraction_activityController extends Controller
{
    public function adminCreateAttraction_activity(Request $request)
    {
        auth()->user();
        //TODO: validate time
        $request->validate(
            [
                "attraction_activity_name" => "required|max:45",
                "opening_time" => "required",
                "closing_time" => "required",
                "description" => "required",
                "city_id" => "required",
                "address" => "required|max:255",
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
        $attraction_activityData = [
            'attraction_activity_name' => $request->attraction_activity_name,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'description' => $request->description,
            'location_id' => $location->id,
        ];
        $createAttraction_activity = attraction_activity::create($attraction_activityData);
        $counter = 1;
        foreach ($request->images as $image) {
            $imagePut = base64_decode($image);
            $extension = ".jpg";
            $filename = $attraction_activityData['attraction_activity_name'] . $counter . $extension;
            $imagePath = public_path('images/attraction_activities/' . $filename);
            $pathToStore = "/images/attraction_activities/" . $filename;
            file_put_contents($imagePath, $imagePut);
            $counter++;
            $imageData = [
                'path_of_image' => $pathToStore, 'attraction_activity_id' => $createAttraction_activity->id
            ];
            $creatImage = image_attraction_activities::create($imageData);
        }
        return response()->json([
            "status" => 1,
            "message" => "attraction_activity created"
        ]);
    }
    public function adminGetAttraction_activites()
    {
    }
    public function adminDeleteAttraction_activity($id)
    {
    }
    // FIXME:
    public function adminUpdateAttraction_activity(Request $request)
    {
        // city_id
        // coordinate_y
        // coordinate_x
        // address
        // attraction_activity_name
        // description
        // closing_time
        // opening_time
        auth()->user();
        if (auth()->user()->type == 2) {
            return response()->json([
                "status" => 0,
                "message" => "you are not admin"
            ]);
        }
        $request->validate([
            "id" => "required|integer",
            "name" => "required|max:45|string",

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
    public function adminGetAttraction_activiteById($id)
    {
    }
}
