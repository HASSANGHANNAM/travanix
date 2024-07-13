<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\avg_rate;
use App\Models\city;
use App\Models\comment;
use App\Models\favorite;
use App\Models\hotel;
use App\Models\hotel_has_services;
use App\Models\image;
use App\Models\location;
use App\Models\room;
use App\Models\image_hotel;
use App\Models\nation;
use App\Models\rate;
use App\Models\service;
use App\Models\tourist;
use App\Models\trip_has_place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use IlluminateSupportFacadesStorage;

class hotelController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('check.admin');
    // }

    public function adminCreateHotel(Request $request)
    {
        auth()->user();
        // dd(auth()->user()->Email_address);
        $request->validate(
            [
                "hotel_name" => "required|max:45|unique:hotel",
                "simple_description_about_hotel" => "required",
                "hotel_class" => "required",
                "phone_number" => "required",
                "city_id" => "required",
                "address" => "required|max:255",
                "coordinate_x" => "required",
                "coordinate_y" => "required",
                "images" => "required|array",
                "services" => "array"
            ]
        );
        $locationData = [
            'city_id' => $request->city_id,
            'address' => $request->address,
            'coordinate_x' => $request->coordinate_x,
            'coordinate_y' => $request->coordinate_y
        ];
        $location = location::create($locationData);
        $hotelData = [
            'hotel_name' => $request->hotel_name,
            'simple_description_about_hotel' => $request->simple_description_about_hotel,
            'location_id' => $location->id,
            'hotel_class' => $request->hotel_class,
            'phone_number' => $request->phone_number,
        ];
        $createHotel = hotel::create($hotelData);
        $counter = 1;
        foreach ($request->images as $image) {
            $imagePut = base64_decode($image);
            $extension = ".jpg";
            $filename = $hotelData['hotel_name'] . $counter . $extension;
            $imagePath = public_path('images/hotels/' . $filename);
            $pathToStore = "/images/hotels/" . $filename;
            file_put_contents($imagePath, $imagePut);
            $counter++;
            $imageData = [
                'path_of_image' => $pathToStore, 'hotel_id' => $createHotel->id
            ];
            $creatImage = image::create($imageData);
        }
        $rateData = [
            'hotel_id' => $createHotel->id,
            'count' => 0,
            'avg' => 0
        ];
        $rate = avg_rate::create($rateData);
        if (isset($request->services)) {
            foreach ($request->services as $service) {
                if (isset($service['service_id'])) {
                    $serviceData = ['service_id' => $service['service_id'], 'hotel_id' => $createHotel->id];
                    $creatHotelHasService = hotel_has_services::create($serviceData);
                } else {
                    $createService = service::create($service);
                    $serviceData = ['service_id' => $createService['id'], 'hotel_id' => $createHotel->id];
                    $creatHotelHasService = hotel_has_services::create($serviceData);
                }
            }
        }
        return response()->json([
            "status" => 1,
            "message" => "hotel created"
        ]);
    }
    public function adminGetHotels()
    {
        auth()->user();
        $hotelData = hotel::with('images')->get();
        $data = [];
        foreach ($hotelData as $h) {
            $location = location::find($h->location_id);
            $city = city::find($location->city_id);
            $nation = nation::find($city->nation_id);
            $servicesId = DB::table('hotel_has_services')->where('hotel_id', $h->id)->get();
            $services = [];
            foreach ($servicesId as $ser) {
                $services[] = service::find($ser->service_id)->service;
            }
            $data[] = [
                'id' => $h->id,
                'address' => $location->address,
                'coordinate_y' => $location->coordinate_y,
                'coordinate_x' => $location->coordinate_x,
                'city_name' => $city->city_name,
                'nation_name' => $nation->nation_name,
                'simple_description_about_hotel' => $h->simple_description_about_hotel,
                'hotel_name' => $h->hotel_name,
                'hotel_class' => $h->hotel_class,
                'phone_number' => $h->phone_number,
                'images' => $h->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "services" => $services
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "hotel get",
            "data" => $data
        ]);
    }
    public function adminCreateRooms(Request $request)
    {
        auth()->user();
        $this->validate($request, [
            '*.size_room' => 'required|integer',
            '*.size_of_bed' => 'required|max:45',
            '*.capacity_room' => 'required|integer',
            '*.price_room' => 'required|integer',
            '*.available_services' => 'required|max:255',
            '*.hotel_id' => 'required|integer'
        ]);
        $rooms = $request->json()->all();
        foreach ($rooms as $room) {
            room::create($room);
        }
        return response()->json([
            "status" => 1,
            "message" => "create rooms"
        ]);
    }
    public function adminDeleteRoom($room_id)
    {
        $room = DB::table('room')->where('id', $room_id)->delete();
        if ($room == 0) {
            return response()->json([
                "status" => 0,
                "message" => "room not found"
            ]);
        }
        return response()->json([
            "status" => 1,
            "message" => "room was deleted"
        ]);
    }
    public function adminUpdateRoom(Request $request)
    {
        auth()->user();
        $this->validate($request, [
            'room_id' => 'required|integer',
            'size_room' => 'integer',
            'size_of_bed' => 'max:45',
            'capacity_room' => 'integer',
            'price_room' => 'integer',
            'available_services' => 'max:255'
        ]);
        if (!room::find($request->room_id)) {
            return response()->json([
                "status" => 0,
                "message" => "room not found"
            ]);
        }
        if (isset($request->size_room)) {
            $update = room::where('id', $request->room_id)->update(array('size_room' => $request->size_room));
        }
        if (isset($request->size_of_bed)) {
            $update = room::where('id', $request->room_id)->update(array('size_of_bed' => $request->size_of_bed));
        }
        if (isset($request->capacity_room)) {
            $update = room::where('id', $request->room_id)->update(array('capacity_room' => $request->capacity_room));
        }
        if (isset($request->price_room)) {
            $update = room::where('id', $request->room_id)->update(array('price_room' => $request->price_room));
        }
        if (isset($request->available_services)) {
            $update = room::where('id', $request->room_id)->update(array('available_services' => $request->available_services));
        }
        return response()->json([
            "status" => 1,
            "message" => "room was updated"
        ]);
    }
    public function adminDeleteHotel($id)
    {
        auth()->user();
        if (!isset($id)) {
            return response()->json([
                "status" => 0,
                "message" => "hotel id not isset"
            ]);
        }
        $find = hotel::find($id);
        if ($find == null) {
            return response()->json([
                "status" => 0,
                "message" => "hotel not found"
            ]);
        }
        $trip = trip_has_place::where('hotel_id', $id)->count();
        if ($trip != 0) {
            return response()->json([
                "status" => 0,
                "message" => "hotel was exist in trip you cannot delete it"
            ]);
        }
        $deleteimage = image::where('hotel_id', $id)->delete();
        $deleteservice = hotel_has_services::where('hotel_id', $id)->delete();
        $deleterate = rate::where('hotel_id', $id)->delete();
        $deletecomment = comment::where('hotel_id', $id)->delete();
        $deletefavorite = favorite::where('hotel_id', $id)->delete();
        $deletehotelrate = avg_rate::where('hotel_id', $id)->delete();
        $hotel = hotel::where('id', $id)->first();
        $deletehotel = hotel::where('id', $id)->delete();
        $deletelocation = location::where('id', $hotel['location_id'])->delete();
        return response()->json([
            "status" => 1,
            "message" => "hotel was deleted"
        ]);
    }
    // TODO:
    public function adminUpdateHotel()
    {
    }
    public function adminGetHotelById($id)
    {
        auth()->user();
        $hotelData = hotel::with('images')->find($id);
        if ($hotelData == null) {
            return response()->json([
                "status" => 0,
                "message" => "hotel not found",
            ]);
        }
        $location = location::find($hotelData->location_id);
        $city = city::find($location->city_id);
        $nation = nation::find($city->nation_id);
        $servicesId = DB::table('hotel_has_services')->where('hotel_id', $hotelData->id)->get();
        $services = [];
        foreach ($servicesId as $ser) {
            $services[] = service::find($ser->service_id)->service;
        }
        $data[] = [
            'id' => $hotelData->id,
            'address' => $location->address,
            'coordinate_y' => $location->coordinate_y,
            'coordinate_x' => $location->coordinate_x,
            'city_name' => $city->city_name,
            'nation_name' => $nation->nation_name,
            'simple_description_about_hotel' => $hotelData->simple_description_about_hotel,
            'hotel_name' => $hotelData->hotel_name,
            'hotel_class' => $hotelData->hotel_class,
            'phone_number' => $hotelData->phone_number,
            'images' => $hotelData->images->map(function ($image) {
                return $image->path_of_image;
            })->all(),
            "services" => $services
        ];
        return response()->json([
            "status" => 1,
            "message" => "hotel get",
            "data" => $data
        ]);
    }
    public function adminGetServices()
    {
        auth()->user();
        $services = DB::table('service')->select('id', 'service')->get();
        return response()->json([
            "status" => 1,
            "message" => "services",
            "data" => $services
        ]);
    }
    public function touristGetHotels()
    {
        auth()->user();
        $hotelData = hotel::with('images')->get();
        $data = [];
        foreach ($hotelData as $h) {
            $location = location::find($h->location_id);
            $city = city::find($location->city_id);
            $nation = nation::find($city->nation_id);
            $servicesId = DB::table('hotel_has_services')->where('hotel_id', $h->id)->get();
            $services = [];
            foreach ($servicesId as $ser) {
                $services[] = service::find($ser->service_id)->service;
            }
            $fav = false;
            if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['hotel_id', $h->id]])->first()) {
                $fav = true;
            }
            $data[] = [
                'id' => $h->id,
                'address' => $location->address,
                'coordinate_y' => $location->coordinate_y,
                'coordinate_x' => $location->coordinate_x,
                'city_name' => $city->city_name,
                'nation_name' => $nation->nation_name,
                'simple_description_about_hotel' => $h->simple_description_about_hotel,
                'hotel_name' => $h->hotel_name,
                'hotel_class' => $h->hotel_class,
                'phone_number' => $h->phone_number,
                'images' => $h->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "services" => $services,
                "favorite" => $fav
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "hotel get",
            "data" => $data
        ]);
    }
    public function touristGetHotelById($id)
    {
        auth()->user();
        $hotelData = hotel::with('images')->find($id);
        if ($hotelData == null) {
            return response()->json([
                "status" => 0,
                "message" => "hotel not found",
            ]);
        }
        $location = location::find($hotelData->location_id);
        $city = city::find($location->city_id);
        $nation = nation::find($city->nation_id);
        $servicesId = DB::table('hotel_has_services')->where('hotel_id', $hotelData->id)->get();
        $services = [];
        foreach ($servicesId as $ser) {
            $services[] = service::find($ser->service_id)->service;
        }
        $fav = false;
        if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['hotel_id', $id]])->first()) {
            $fav = true;
        }
        $data[] = [
            'id' => $hotelData->id,
            'address' => $location->address,
            'coordinate_y' => $location->coordinate_y,
            'coordinate_x' => $location->coordinate_x,
            'city_name' => $city->city_name,
            'nation_name' => $nation->nation_name,
            'simple_description_about_hotel' => $hotelData->simple_description_about_hotel,
            'hotel_name' => $hotelData->hotel_name,
            'hotel_class' => $hotelData->hotel_class,
            'phone_number' => $hotelData->phone_number,
            'images' => $hotelData->images->map(function ($image) {
                return $image->path_of_image;
            })->all(),
            "services" => $services,
            "favorite" => $fav

        ];
        return response()->json([
            "status" => 1,
            "message" => "hotel get",
            "data" => $data
        ]);
    }
}
