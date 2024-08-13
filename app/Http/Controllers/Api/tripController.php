<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\avg_rate;
use App\Models\city;
use App\Models\favorite;
use App\Models\location;
use App\Models\nation;
use App\Models\resturant;
use App\Models\tourist;
use App\Models\tourist_details;
use App\Models\tourist_has_trip;
use App\Models\trip;
use App\Models\trip_has_place;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class tripController extends Controller
{
    public function adminCreateTrip(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "trip_name" => "required|max:45",
                "type_of_trip" => "required|max:45",
                "description" => "required",
                "price_trip" => "required",
                "number_of_allSeat" => "required|integer",
                "trip_start_time" => "required",
                "trip_end_time" => "required",
                "city_id" => "required",
                "address" => "required|max:255",
                "coordinate_x" => "required",
                "coordinate_y" => "required",
                'places.*.hotel_id' => 'integer',
                'places.*.resturant_id' => 'integer',
                'places.*.attraction_activity_id' => 'integer',
            ]
        );
        $Date = Carbon::create(substr($request->trip_start_time, 0, 4), substr($request->trip_start_time, 5, 2), substr($request->trip_start_time, 8, 2), substr($request->trip_start_time, 11, 2), substr($request->trip_start_time, 14, 2), substr($request->trip_start_time, 17, 2));
        $now = Carbon::now();
        if ($Date->diffInHours($now) < 24 || $Date->lessThan($now)) {
            return response()->json([
                "status" => 0,
                "message" => "time off less than 24 houre"
            ]);
        }
        $locationData = [
            'city_id' => $request->city_id,
            'address' => $request->address,
            'coordinate_x' => $request->coordinate_x,
            'coordinate_y' => $request->coordinate_y
        ];
        $location = location::create($locationData);
        $tripData = [
            'type_of_trip' => $request->type_of_trip,
            'trip_name' => $request->trip_name,
            'description' => $request->description,
            'price_trip' => $request->price_trip,
            'number_of_allSeat' => $request->number_of_allSeat,
            'trip_start_time' => $request->trip_start_time,
            'trip_end_time' => $request->trip_end_time,
            'location_id' => $location->id,
        ];
        $trip = trip::create($tripData);
        foreach ($request->places as $place) {
            if (isset($place['hotel_id'])) {
                $trip_has_placeData = [
                    'hotel_id' => $place['hotel_id'],
                    'trip_id' => $trip->id
                ];
                $create_trip_has_place = trip_has_place::create($trip_has_placeData);
            }
            if (isset($place['resturant_id'])) {
                $trip_has_placeData = [
                    'resturant_id' => $place['resturant_id'],
                    'trip_id' => $trip->id
                ];
                $create_trip_has_place = trip_has_place::create($trip_has_placeData);
            }
            if (isset($place['attraction_activity_id'])) {
                $trip_has_placeData = [
                    'attraction_activity_id' => $place['attraction_activity_id'],
                    'trip_id' => $trip->id
                ];
                $create_trip_has_place = trip_has_place::create($trip_has_placeData);
            }
        }
        $rateData = [
            'trip_id' => $trip->id,
            'count' => 0,
            'avg' => 0
        ];
        $rate = avg_rate::create($rateData);
        return response()->json([
            "status" => 1,
            "message" => "trip created"
        ]);
    }
    public function adminGetTrips()
    {
        auth()->user();
        $tripData = trip::with('places')->get();
        $data = [];
        foreach ($tripData as $t) {
            $sum = tourist_has_trip::where([['trip_id', $t->id], ['status', "!=", "Canceled"]])->sum('number_of_seat');
            $number_of_seats_available = $t->number_of_allSeat - $sum;
            $location = location::find($t->location_id);
            $city = city::find($location->city_id);
            $nation = nation::find($city->nation_id);
            $image = trip_has_place::where('trip_id', $t->id)
                ->join('attraction_activities', 'trip_has_place.attraction_activity_id', '=', 'attraction_activities.id')
                ->join('image', 'attraction_activities.id', '=', 'image.attraction_activity_id')
                ->select('image.path_of_image')
                ->first();
            $data[] = [
                'id' => $t->id,
                'type_of_trip' => $t->type_of_trip,
                'trip_name' => $t->trip_name,
                'description' => $t->description,
                'price_trip' => $t->price_trip,
                'number_of_allSeat' => $t->number_of_allSeat,
                'trip_start_time' => $t->trip_start_time,
                'trip_end_time' => $t->trip_end_time,
                "city_id" => $city->id,
                "city_name" => $city->city_name,
                "nation_id" => $nation->id,
                "nation_name" => $nation->nation_name,
                "address" => $location->address,
                "coordinate_x" => $location->coordinate_x,
                "coordinate_y" => $location->coordinate_y,
                "image" => $image->path_of_image,
                'places' => $t->places->map(function ($place) {
                    if (isset($place->hotel_id))
                        return ['hotel_id' => $place->hotel_id];
                    if (isset($place->attraction_activity_id))
                        return ['attraction_activity_id' => $place->attraction_activity_id];
                    if (isset($place->resturant_id))
                        return ['resturant_id' => $place->resturant_id];
                })->all(),
                "number_of_seats_available" => $number_of_seats_available
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "trip gets",
            "data" => $data
        ]);
    }
    public function adminDeleteTrip($id)
    {
        auth()->user();
        if (!isset($id)) {
            return response()->json([
                "status" => 0,
                "message" => "trip id not isset"
            ]);
        }
        $find = trip::find($id);
        if ($find == null) {
            return response()->json([
                "status" => 0,
                "message" => "trip not found"
            ]);
        }
        $Date = Carbon::create(substr($find->trip_start_time, 0, 4), substr($find->trip_start_time, 5, 2), substr($find->trip_start_time, 8, 2), substr($find->trip_start_time, 11, 2), substr($find->trip_start_time, 14, 2), substr($find->trip_start_time, 17, 2));
        $now = Carbon::now();
        if ($Date->lessThan($now)) {
            return response()->json([
                "status" => 0,
                "message" => "time of trip was end"
            ]);
        }
        $trip = trip_has_place::where('trip_id', $id)->delete();
        $deletefavorite = favorite::where('trip_id', $id)->delete();
        $tourist_has_trip = tourist_has_trip::where('trip_id', $id)->get();
        foreach ($tourist_has_trip as $t) {
            $deletet = tourist_details::where('tourist_has_trip_id', $t->id)->delete();
            if ($t->status == "Submitted") {
                $findwallet = tourist::find($t->tourist_id);
                $update = tourist::where('id', $findwallet->id)->update(array('wallet' => $findwallet->wallet + $t->number_of_seat * $find->price_trip));
            }
            $delete = tourist_has_trip::where('id', $t->id)->delete();
        }
        $deletetriprate = avg_rate::where('trip_id', $id)->delete();
        $deletetrip = trip::where('id', $id)->delete();
        $deletelocation = location::where('id', $find['location_id']);
        return response()->json([
            "status" => 1,
            "message" => "trip was deleted"
        ]);
    }
    public function adminUpdateTrip(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "trip_id" => "required|integer",
                "trip_name" => "",
                "description" => "max:255",
                "type_of_trip" => "max:45",
                "price_trip" => "",
                "number_of_allSeat" => "integer",
                "trip_start_time" => "",
                "trip_end_time" => "",
                "city_id" => "integer",
                "address" => "max:255",
                "coordinate_x" => "",
                "coordinate_y" => "",
                'places.*.hotel_id' => 'integer',
                'places.*.resturant_id' => 'integer',
                'places.*.attraction_activity_id' => 'integer',
            ]
        );
        $trip = trip::find($request->trip_id);
        if ($trip == null) {
            return response()->json([
                "status" => 0,
                "message" => "trip not found"
            ]);
        }
        $Date = Carbon::create(substr($trip->trip_start_time, 0, 4), substr($trip->trip_start_time, 5, 2), substr($trip->trip_start_time, 8, 2), substr($trip->trip_start_time, 11, 2), substr($trip->trip_start_time, 14, 2), substr($trip->trip_start_time, 17, 2));
        $now = Carbon::now();
        if ($Date->diffInHours($now) < 12 || $Date->lessThan($now)) {
            return response()->json([
                "status" => 0,
                "message" => "time of reserve was end"
            ]);
        }
        if (isset($request->trip_name)) {
            if ($request->trip_name != $trip['trip_name']) {
                $request->validate(
                    [
                        "trip_name" => "unique"
                    ]
                );
                $update = trip::where('id', $request->trip_id)->update(array('trip_name' => $request->trip_name));
            }
        }
        if (isset($request->city_id) && !city::find($request->city_id)) {
            return response()->json([
                "status" => 0,
                "message" => "city not found"
            ]);
        }
        if (isset($request->type_of_trip)) {
            trip::where('id', $request->trip_id)->update(array('type_of_trip' => $request->type_of_trip));
        }
        if (isset($request->price_trip)) {
            trip::where('id', $request->trip_id)->update(array('price_trip' => $request->price_trip));
        }
        if (isset($request->number_of_allSeat)) {
            trip::where('id', $request->trip_id)->update(array('number_of_allSeat' => $request->number_of_allSeat));
        }
        if (isset($request->trip_start_time)) {
            trip::where('id', $request->trip_id)->update(array('trip_start_time' => $request->trip_start_time));
        }
        if (isset($request->trip_end_time)) {
            trip::where('id', $request->trip_id)->update(array('trip_end_time' => $request->trip_end_time));
        }
        if (isset($request->description)) {
            trip::where('id', $request->trip_id)->update(array('description' => $request->description));
        }
        $location = location::find($trip->location_id);
        if (isset($request->city_id)) {
            location::where('id', $location->id)->update(array('city_id' => $request->city_id));
        }
        if (isset($request->address)) {
            location::where('id', $location->id)->update(array('address' => $request->address));
        }
        if (isset($request->coordinate_x)) {
            location::where('id', $location->id)->update(array('coordinate_x' => $request->coordinate_x));
        }
        if (isset($request->coordinate_y)) {
            location::where('id', $location->id)->update(array('coordinate_y' => $request->coordinate_y));
        }
        if (isset($request->places)) {
            trip_has_place::where('trip_id', $request->trip_id)->delete();
            foreach ($request->places as $place) {
                if (isset($place['hotel_id'])) {
                    $trip_has_placeData = [
                        'hotel_id' => $place['hotel_id'],
                        'trip_id' => $trip->id
                    ];
                    $create_trip_has_place = trip_has_place::create($trip_has_placeData);
                }
                if (isset($place['resturant_id'])) {
                    $trip_has_placeData = [
                        'resturant_id' => $place['resturant_id'],
                        'trip_id' => $trip->id
                    ];
                    $create_trip_has_place = trip_has_place::create($trip_has_placeData);
                }
                if (isset($place['attraction_activity_id'])) {
                    $trip_has_placeData = [
                        'attraction_activity_id' => $place['attraction_activity_id'],
                        'trip_id' => $trip->id
                    ];
                    $create_trip_has_place = trip_has_place::create($trip_has_placeData);
                }
            }
        }
        return response()->json([
            "status" => 1,
            "message" => "trip updated"
        ]);
    }
    public function adminGetTripById($id)
    {
        auth()->user();
        $tripData = trip::with('places')->find($id);
        if ($tripData == null) {
            return response()->json([
                "status" => 0,
                "message" => "trip not found",
            ]);
        }
        $sum = tourist_has_trip::where([['trip_id', $tripData->id], ['status', "!=", "Canceled"]])->sum('number_of_seat');
        $number_of_seats_available = $tripData->number_of_allSeat - $sum;
        $location = location::find($tripData->location_id);
        $city = city::find($location->city_id);
        $nation = nation::find($city->nation_id);
        $image = trip_has_place::where('trip_id', $tripData->id)
            ->join('attraction_activities', 'trip_has_place.attraction_activity_id', '=', 'attraction_activities.id')
            ->join('image', 'attraction_activities.id', '=', 'image.attraction_activity_id')
            ->select('image.path_of_image')
            ->first();
        $data = [
            'id' => $tripData->id,
            'type_of_trip' => $tripData->type_of_trip,
            'trip_name' => $tripData->trip_name,
            'description' => $tripData->description,
            'price_trip' => $tripData->price_trip,
            'number_of_allSeat' => $tripData->number_of_allSeat,
            'trip_start_time' => $tripData->trip_start_time,
            'trip_end_time' => $tripData->trip_end_time,
            "city_id" => $city->id,
            "city_name" => $city->city_name,
            "nation_id" => $nation->id,
            "nation_name" => $nation->nation_name,
            "address" => $location->address,
            "coordinate_x" => $location->coordinate_x,
            "coordinate_y" => $location->coordinate_y,
            "image" => $image->path_of_image,
            'places' => $tripData->places->map(function ($place) {
                if (isset($place->hotel_id))
                    return ['hotel_id' => $place->hotel_id];
                if (isset($place->attraction_activity_id))
                    return ['attraction_activity_id' => $place->attraction_activity_id];
                if (isset($place->resturant_id))
                    return ['resturant_id' => $place->resturant_id];
            })->all(),
            "number_of_seats_available" => $number_of_seats_available
        ];
        return response()->json([
            "status" => 1,
            "message" => "trip gets",
            "data" => $data
        ]);
    }
    public function adminGetTripsReserved()
    {
        auth()->user();
        $tripReserved = tourist_has_trip::all();
        $data = [];
        foreach ($tripReserved as $tr) {
            $tripData = app(tripController::class)->adminGetTripById($tr->trip_id);
            $tourist_id = $tr->tourist_id;
            $dataoftourist = DB::table('tourist')
                ->join('users', function ($join) use ($tourist_id) {
                    $join->on('tourist.user_id', '=', 'users.id')
                        ->where('tourist.id', '=', $tourist_id);
                })
                ->first();
            $data[] = [
                'id' => $tripData->original['data']['id'],
                'reserve_id' => $tr->id,
                'type_of_trip' => $tripData->original['data']['type_of_trip'],
                'trip_name' => $tripData->original['data']['trip_name'],
                'description' => $tripData->original['data']['description'],
                'price_trip' => $tripData->original['data']['price_trip'],
                'number_of_allSeat' => $tripData->original['data']['number_of_allSeat'],
                'trip_start_time' => $tripData->original['data']['trip_start_time'],
                'trip_end_time' => $tripData->original['data']['trip_end_time'],
                "city_id" => $tripData->original['data']['city_id'],
                "city_name" => $tripData->original['data']['city_name'],
                "nation_id" => $tripData->original['data']['nation_id'],
                "nation_name" => $tripData->original['data']['nation_name'],
                "address" => $tripData->original['data']['address'],
                "coordinate_x" => $tripData->original['data']['coordinate_x'],
                "coordinate_y" => $tripData->original['data']['coordinate_y'],
                "image" => $tripData->original['data']['image'],
                'places' => $tripData->original['data']['places'],
                "number_of_seats_available" => $tripData->original['data']['number_of_seats_available'],
                "status" => $tr->status,
                "number_of_seat_reserved" => $tr->number_of_seat,
                "phone_number" => $tr->phone_number,
                "Email_address" => $dataoftourist->Email_address,
                "tourist_name" => $dataoftourist->tourist_name,
                "wallet" => $dataoftourist->wallet,
                'details' => $tr->details->map(function ($detail) {
                    return ['name' => $detail->name, 'age' => $detail->age, 'id' => $detail->id];
                })->all()
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "trip gets",
            "data" => $data
        ]);
    }
    public function adminUpdateTripReserved(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "id" => "required|integer",
                "status" => "required",
            ]
        );
        $find = tourist_has_trip::find($request->id);
        if ($find == null) {
            return response()->json([
                "status" => 0,
                "message" => "id not found"
            ]);
        }

        $trip = trip::find($find->trip_id);
        $Date = Carbon::create(substr($trip->trip_start_time, 0, 4), substr($trip->trip_start_time, 5, 2), substr($trip->trip_start_time, 8, 2), substr($trip->trip_start_time, 11, 2), substr($trip->trip_start_time, 14, 2), substr($trip->trip_start_time, 17, 2));
        $now = Carbon::now();
        if ($Date->lessThan($now)) {
            return response()->json([
                "status" => 0,
                "message" => "time of trip was end"
            ]);
        }
        if ($find['status'] == "Submitted" || $find['status'] == "Canceled") {
            return response()->json([
                "status" => 0,
                "message" => "this request was handler"
            ]);
        }
        if ($request->status != "Submitted" && $request->status != "Canceled") {
            return response()->json([
                "status" => 0,
                "message" => "your post  status not found in system"
            ]);
        }
        if ($request->status == "Submitted") {
            $oldWallet = DB::table('tourist')->select('wallet')->where('id', $find->tourist_id)->first();
            $price = DB::table('trip')->select('price_trip')->where('id', $find->trip_id)->first();
            $newwallet = $oldWallet->wallet - ($price->price_trip * $find->number_of_seat);
            if ($newwallet >= 0) {
                $updateWallet = tourist::where('id', $find->tourist_id)->update(array('wallet' => $newwallet));
            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "your  wallet less than price trip"
                ]);
            }
        }
        $status = tourist_has_trip::where('id', $request->id)->update(array('status' => $request->status));
        return response()->json([
            "status" => 1,
            "message" => "you update status"
        ]);
    }
    public function touristGetTrips()
    {
        auth()->user();
        $trip = trip::with('places')->get();
        $data = [];
        foreach ($trip as $t) {
            $fav = false;
            if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['trip_id', $t->id]])->first()) {
                $fav = true;
            }
            $sum = tourist_has_trip::where([['trip_id', $t->id], ['status', "!=", "Canceled"]])->sum('number_of_seat');
            $number_of_seats_available = $t->number_of_allSeat - $sum;
            $location = location::find($t->location_id);
            $city = city::find($location->city_id);
            $nation = nation::find($city->nation_id);
            $image = trip_has_place::where('trip_id', $t->id)
                ->join('attraction_activities', 'trip_has_place.attraction_activity_id', '=', 'attraction_activities.id')
                ->join('image', 'attraction_activities.id', '=', 'image.attraction_activity_id')
                ->select('image.path_of_image')
                ->first();
            $data[] = [
                'id' => $t->id,
                'type_of_trip' => $t->type_of_trip,
                'trip_name' => $t->trip_name,
                'description' => $t->description,
                'price_trip' => $t->price_trip,
                'number_of_allSeat' => $t->number_of_allSeat,
                'trip_start_time' => $t->trip_start_time,
                'trip_end_time' => $t->trip_end_time,
                "city_id" => $city->id,
                "city_name" => $city->city_name,
                "nation_id" => $nation->id,
                "nation_name" => $nation->nation_name,
                "address" => $location->address,
                "coordinate_x" => $location->coordinate_x,
                "coordinate_y" => $location->coordinate_y,
                "image" => $image->path_of_image,
                'places' => $t->places->map(function ($place) {
                    if (isset($place->hotel_id))
                        return ['hotel_id' => $place->hotel_id];
                    if (isset($place->attraction_activity_id))
                        return ['attraction_activity_id' => $place->attraction_activity_id];
                    if (isset($place->resturant_id))
                        return ['resturant_id' => $place->resturant_id];
                })->all(),
                "favorite" => $fav,
                "number_of_seats_available" => $number_of_seats_available

            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "trip gets",
            "data" => $data
        ]);
    }
    public function touristGetTripsReserved()
    {
        auth()->user();
        // $touristDet = tourist_has_trip::with('details')->get();
        $touristId = DB::table('tourist')->where('user_id', auth()->user()->id)->first();
        $tripReserved = tourist_has_trip::where('tourist_id', $touristId->id)->get();
        $data = [];
        foreach ($tripReserved as $tr) {
            $tripData = app(tripController::class)->touristGetTripById($tr->trip_id);
            $data[] = [
                'id' => $tripData->original['data']['id'],
                'reserve_id' => $tr->id,
                'type_of_trip' => $tripData->original['data']['type_of_trip'],
                'trip_name' => $tripData->original['data']['trip_name'],
                'description' => $tripData->original['data']['description'],
                'price_trip' => $tripData->original['data']['price_trip'],
                'number_of_allSeat' => $tripData->original['data']['number_of_allSeat'],
                'trip_start_time' => $tripData->original['data']['trip_start_time'],
                'trip_end_time' => $tripData->original['data']['trip_end_time'],
                "city_id" => $tripData->original['data']['city_id'],
                "city_name" => $tripData->original['data']['city_name'],
                "nation_id" => $tripData->original['data']['nation_id'],
                "nation_name" => $tripData->original['data']['nation_name'],
                "address" => $tripData->original['data']['address'],
                "coordinate_x" => $tripData->original['data']['coordinate_x'],
                "coordinate_y" => $tripData->original['data']['coordinate_y'],
                "image" => $tripData->original['data']['image'],
                'places' => $tripData->original['data']['places'],
                "favorite" => $tripData->original['data']['favorite'],
                "number_of_seats_available" => $tripData->original['data']['number_of_seats_available'],
                "status" => $tr->status,
                "number_of_seat_reserved" => $tr->number_of_seat,
                "phone_number" => $tr->phone_number,
                'details' => $tr->details->map(function ($detail) {
                    return [$detail->name, $detail->age, $detail->id];
                })->all()
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "trip gets",
            "data" => $data
        ]);
    }
    public function touristGetTripById($id)
    {
        auth()->user();
        $tripData = trip::with('places')->find($id);
        if ($tripData == null) {
            return response()->json([
                "status" => 0,
                "message" => "trip not found",
            ]);
        }
        $fav = false;
        if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['trip_id', $tripData->id]])->first()) {
            $fav = true;
        }
        $sum = tourist_has_trip::where([['trip_id', $tripData->id], ['status', "!=", "Canceled"]])->sum('number_of_seat');
        $number_of_seats_available = $tripData->number_of_allSeat - $sum;
        $location = location::find($tripData->location_id);
        $city = city::find($location->city_id);
        $nation = nation::find($city->nation_id);
        $image = trip_has_place::where('trip_id', $tripData->id)
            ->join('attraction_activities', 'trip_has_place.attraction_activity_id', '=', 'attraction_activities.id')
            ->join('image', 'attraction_activities.id', '=', 'image.attraction_activity_id')
            ->select('image.path_of_image')
            ->first();
        $data = [
            'id' => $tripData->id,
            'type_of_trip' => $tripData->type_of_trip,
            'trip_name' => $tripData->trip_name,
            'description' => $tripData->description,
            'price_trip' => $tripData->price_trip,
            'number_of_allSeat' => $tripData->number_of_allSeat,
            'trip_start_time' => $tripData->trip_start_time,
            'trip_end_time' => $tripData->trip_end_time,
            "city_id" => $city->id,
            "city_name" => $city->city_name,
            "nation_id" => $nation->id,
            "nation_name" => $nation->nation_name,
            "address" => $location->address,
            "coordinate_x" => $location->coordinate_x,
            "coordinate_y" => $location->coordinate_y,
            "image" => $image->path_of_image,
            'places' => $tripData->places->map(function ($place) {
                if (isset($place->hotel_id))
                    return ['hotel_id' => $place->hotel_id];
                if (isset($place->attraction_activity_id))
                    return ['attraction_activity_id' => $place->attraction_activity_id];
                if (isset($place->resturant_id))
                    return ['resturant_id' => $place->resturant_id];
            })->all(),
            "favorite" => $fav,
            "number_of_seats_available" => $number_of_seats_available
        ];
        return //$data;
            response()->json([
                "status" => 1,
                "message" => "trip gets",
                "data" => $data
            ]);
    }
    public function touristReserveTrip(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "trip_id" => "required|max:45",
                "number_of_seat" => "required",
                "phone_number" => "required",
                'detalis.*.name' => 'required|max:45',
                'detalis.*.age' => 'required|integer'
            ]
        );
        $tripData = trip::find($request->trip_id);
        if ($tripData == null) {
            return response()->json([
                "status" => 0,
                "message" => "trip not found",
            ]);
        }
        $Date = Carbon::create(substr($tripData->trip_start_time, 0, 4), substr($tripData->trip_start_time, 5, 2), substr($tripData->trip_start_time, 8, 2), substr($tripData->trip_start_time, 11, 2), substr($tripData->trip_start_time, 14, 2), substr($tripData->trip_start_time, 17, 2));
        $now = Carbon::now();
        if ($Date->diffInHours($now) < 12 || $Date->lessThan($now)) {
            return response()->json([
                "status" => 0,
                "message" => "time of reserve was end"
            ]);
        }
        $count = count($request->detalis);
        if ($count < $request->number_of_seat) {
            return response()->json([
                "status" => 0,
                "message" => "detalis was less than number_of_seat"
            ]);
        }
        if ($count > $request->number_of_seat) {
            return response()->json([
                "status" => 0,
                "message" => "detalis was more than number_of_seat"
            ]);
        }
        $sum = tourist_has_trip::where([['trip_id', $tripData->id], ['status', "!=", "Canceled"]])->sum('number_of_seat');
        $number_of_seats_available = $tripData->number_of_allSeat - $sum;
        if ($request->number_of_seat > $number_of_seats_available) {
            return response()->json([
                "status" => 0,
                "message" => "number of seat not  found",
            ]);
        }
        $touristId = DB::table('tourist')->where('user_id', auth()->user()->id)->first();
        $price = DB::table('trip')->select('price_trip')->where('id', $request->trip_id)->first();
        $newwallet = $touristId->wallet - ($price->price_trip * $request->number_of_seat);
        if ($newwallet < 0) {
            return response()->json([
                "status" => 0,
                "message" => "wallet less than price"
            ], 402);
        }
        $tourist_has_tripData = [
            "trip_id" => $request->trip_id,
            "tourist_id" => $touristId->id,
            "number_of_seat" => $request->number_of_seat,
            "phone_number" => $request->phone_number,
            "status" => "Pending"
        ];
        $create = tourist_has_trip::create($tourist_has_tripData);
        foreach ($request->detalis as $d) {
            $detalisData = [
                "name" => $d['name'],
                "age" => $d['age'],
                "tourist_has_trip_id" => $create->id
            ];
            tourist_details::create($detalisData);
        }
        return response()->json([
            "status" => 1,
            "message" => "trip Reserved",
        ]);
    }
    public function touristDeleteReserveTrip($id)
    {
        auth()->user();
        if (!isset($id)) {
            return response()->json([
                "status" => 0,
                "message" => "trip reserved id not isset"
            ]);
        }
        $find = tourist_has_trip::find($id);
        if ($find == null) {
            return response()->json([
                "status" => 0,
                "message" => "trip reserved not found"
            ]);
        }
        $trip = trip::find($find->trip_id);
        $Date = Carbon::create(substr($trip->trip_start_time, 0, 4), substr($trip->trip_start_time, 5, 2), substr($trip->trip_start_time, 8, 2), substr($trip->trip_start_time, 11, 2), substr($trip->trip_start_time, 14, 2), substr($trip->trip_start_time, 17, 2));
        $now = Carbon::now();
        if ($Date->lessThan($now)) {
            return response()->json([
                "status" => 0,
                "message" => "time of trip was end"
            ]);
        }
        if ($find->status == "Submitted") {
            $findwallet = DB::table('tourist')->where('user_id', auth()->user()->id)->first();
            $yourDate = $trip->trip_start_time;
            $Date = Carbon::create(substr($yourDate, 0, 4), substr($yourDate, 5, 2), substr($yourDate, 8, 2), substr($yourDate, 11, 2), substr($yourDate, 14, 2), substr($yourDate, 17, 2));
            $now = Carbon::now();
            if ($Date->greaterThan($now) && $Date->diffInHours($now) > 12) {
                $update = tourist::where('id', $findwallet->id)->update(array('wallet' => $findwallet->wallet + $find->number_of_seat * $trip->price_trip));
            }
        }
        $deletedeta = tourist_details::where('tourist_has_trip_id', $find->id)->delete();
        $delete = tourist_has_trip::where('id', $id)->delete();
        return response()->json([
            "status" => 1,
            "message" => "trip reserved was deleted"
        ]);
    }
    public function touristUpdateReserveTrip(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "id" => "required|integer",
                "number_of_seat" => "",
                "phone_number" => "",
                'detalis.*.name' => 'max:45',
                'detalis.*.age' => 'integer'
            ]
        );
        $reserve = DB::table('tourist_has_trip')->where('id', $request->id)->first();
        if ($reserve == null) {
            return response()->json([
                "status" => 0,
                "message" => "reserve not found"
            ]);
        }
        $tripData = trip::find($reserve->trip_id);
        if ($tripData == null) {
            return response()->json([
                "status" => 0,
                "message" => "trip not found",
            ]);
        }
        $Date = Carbon::create(substr($tripData->trip_start_time, 0, 4), substr($tripData->trip_start_time, 5, 2), substr($tripData->trip_start_time, 8, 2), substr($tripData->trip_start_time, 11, 2), substr($tripData->trip_start_time, 14, 2), substr($tripData->trip_start_time, 17, 2));
        $now = Carbon::now();
        if ($Date->diffInHours($now) < 12 || $Date->lessThan($now)) {
            return response()->json([
                "status" => 0,
                "message" => "time of reserve was end"
            ]);
        };
        if (isset($request->detalis) && isset($request->number_of_seat)) {
            $count = count($request->detalis);
            if ($count < $request->number_of_seat) {
                return response()->json([
                    "status" => 0,
                    "message" => "detalis was less than number_of_seat"
                ]);
            }
            if ($count > $request->number_of_seat) {
                return response()->json([
                    "status" => 0,
                    "message" => "detalis was more than number_of_seat"
                ]);
            }
            $sum = tourist_has_trip::where('trip_id', $tripData->id)->sum('number_of_seat');
            $number_of_seats_available = $tripData->number_of_allSeat - $sum + $reserve->number_of_seat;
            if ($request->number_of_seat > $number_of_seats_available) {
                return response()->json([
                    "status" => 0,
                    "message" => "number of seat not  found",
                ]);
            }
            $findwallet = DB::table('tourist')->where('user_id', auth()->user()->id)->first();
            if ($reserve->status == "Submitted") {
                if ($findwallet->wallet + $reserve->number_of_seat * $tripData->price_trip < $request->number_of_seat * $tripData->price_trip) {
                    return response()->json([
                        "status" => 0,
                        "message" => "wallet less than price"
                    ], 402);
                }
                $update = tourist::where('id', $findwallet->id)->update(array('wallet' => $findwallet->wallet + $reserve->number_of_seat * $tripData->price_trip));
            } elseif ($findwallet->wallet < $request->number_of_seat * $tripData->price_trip) {
                return response()->json([
                    "status" => 0,
                    "message" => "wallet less than price"
                ], 402);
            }
            $delete = tourist_details::where('tourist_has_trip_id', $request->id)->delete();
            foreach ($request->detalis as $d) {
                $detalisData = [
                    "name" => $d['name'],
                    "age" => $d['age'],
                    "tourist_has_trip_id" => $request->id
                ];
                tourist_details::create($detalisData);
            }
            $update = DB::table('tourist_has_trip')->where('id', $reserve->id)->update(array('status' => "Pending"));
        } elseif (isset($request->detalis) || isset($request->number_of_seat)) {
            return response()->json([
                "status" => 0,
                "message" => "you must to send detalis and number_of_seat",
            ]);
        }
        if (isset($request->phone_number)) {
            tourist_has_trip::where('id', $request->id)->update(array('phone_number' => $request->phone_number));
        }
        return response()->json([
            "status" => 1,
            "message" => "Reserved update",
        ]);
    }
}
