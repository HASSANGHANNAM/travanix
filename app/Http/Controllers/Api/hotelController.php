<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\hotel;
use App\Models\location;
use App\Models\room;
use App\Models\image_hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use IlluminateSupportFacadesStorage;

class hotelController extends Controller
{
    public function adminCreateHotel(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "hotel_name" => "required|max:45",
                "simple_description_about_hotel" => "required",
                "reviews_about_hotel" => "required",
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
        $hotelData = [
            'hotel_name' => $request->hotel_name,
            'simple_description_about_hotel' => $request->simple_description_about_hotel,
            'reviews_about_hotel' => $request->reviews_about_hotel,
            'location_id' => $location->id,
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
            $creatImage = image_hotel::create($imageData);
        }
        return response()->json([
            "status" => 1,
            "message" => "hotel created"
        ]);
    }
    public function adminGetHotels()
    {
        auth()->user();
        $data = hotel::join('room', 'room.hotel_id', 'hotel.country_id')
            ->join('city', 'city.state_id', '=', 'state.state_id')
            ->get(['country.country_name', 'state.state_name', 'city.city_name']);
        $allHotel = DB::table('hotel')->get();
        dd($allHotel);
        return response()->json([
            "status" => 1,
            "message" => "hotel get"
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
    public function adminDeleteRoom($hotel_id, $room_id)
    {
    }
    public function adminUpdateRoom()
    {
    }
    public function adminDeleteHotel($id)
    {
    }
    public function adminUpdateHotel()
    {
    }
    public function adminGetHotelById($id)
    {
    }
}
