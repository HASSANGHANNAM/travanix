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
use App\Models\reserve;
use App\Models\reserve_has_room;
use App\Models\service;
use App\Models\tourist;
use App\Models\trip_has_place;
use App\Models\User;
use GuzzleHttp\Promise\Create;
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
        $room = $request->validate(
            [
                'capacity_room' => 'required|integer',
                'price_room' => 'required',
                'quantity' => 'required|integer',
                'hotel_id' => 'required|integer'
            ]
        );
        $findRoom = DB::table('room')->where([['hotel_id',  $request->hotel_id], ['capacity_room', $request->capacity_room]])->first();
        if ($findRoom != null) {
            return response()->json([
                "status" => 0,
                "message" => "there is similar capacity of room before to add number of room use add room"
            ]);
        }
        if (hotel::find($request->hotel_id) == null) {
            return response()->json([
                "status" => 0,
                "message" => "hotel not found "
            ]);
        }
        room::create($room);
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
        $request->validate(
            [
                'room_id' => 'required|integer',
                'price_room' => 'required',
            ]
        );
        if (!room::find($request->room_id)) {
            return response()->json([
                "status" => 0,
                "message" => "room not found"
            ]);
        }
        if (isset($request->price_room)) {
            $update = room::where('id', $request->room_id)->update(array('price_room' => $request->price_room));
        }
        return response()->json([
            "status" => 1,
            "message" => "price room was updated"
        ]);
    }
    public function adminAddRooms(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                'room_id' => 'required|integer',
                'number' => 'required|integer',
            ]
        );
        $find = room::find($request->room_id);
        if ($find == null) {
            return response()->json([
                "status" => 0,
                "message" => "room not found"
            ]);
        }
        if (isset($request->number)) {
            $update = room::where('id', $request->room_id)->update(array('quantity' => $request->number + $find->quantity));
        }
        return response()->json([
            "status" => 1,
            "message" => "quantity was updated"
        ]);
    }
    public function adminGetRooms($hotel_id)
    {
        auth()->user();
        $find = hotel::find($hotel_id);
        if ($find == null) {
            return response()->json([
                "status" => 0,
                "message" => "hotel not found"
            ]);
        }
        $data = DB::table('room')->where('hotel_id', $hotel_id)->select('id', 'quantity', 'capacity_room', 'price_room', 'hotel_id')->get();
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $data
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
    public function touristCheckReserve(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "hotel_id" => "required|integer",
                "start_reservation" => "required",
                "end_reservation" => "required",
                'rooms.*.capacity_room' => 'integer|required',
                'rooms.*.number_of_room' => 'integer|required'
            ]
        );
        $find = hotel::find($request->hotel_id);
        if ($find == null) {
            return response()->json([
                "status" => 0,
                "message" => "hotel not found",
            ]);
        }
        $startDate = $request->start_reservation;
        $endDate = $request->end_reservation;
        $price_all_reserve = 0;
        foreach ($request->rooms as $room) {
            $capacity = $room['capacity_room'];
            if ($room['number_of_room'] == 0) {
                continue;
            }
            $notAvailableRooms = DB::table('hotel')
                ->join('room', 'hotel.id', '=', 'room.hotel_id')
                ->leftJoin('reserve_has_room', 'room.id', '=', 'reserve_has_room.room_id')
                ->join('reserve', 'reserve_has_room.reserve_id', '=', 'reserve.id')
                ->where('capacity_room', $capacity)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereRaw('start_reservation < ?', [$startDate])
                        ->whereRaw('end_reservation > ?', [$endDate])
                        ->orWhere(function ($subQuery)  use ($startDate, $endDate) {
                            $subQuery->whereRaw('start_reservation > ?', [$startDate])
                                ->whereRaw('start_reservation <?', [$endDate]);
                        })
                        ->orWhere(function ($subQuery)  use ($startDate, $endDate) {
                            $subQuery->whereRaw('end_reservation > ?', [$startDate])
                                ->whereRaw('end_reservation < ?', [$endDate]);
                        })
                        ->orWhere(function ($subQuery)  use ($startDate, $endDate) {
                            $subQuery->whereRaw('start_reservation > ?', [$startDate])
                                ->whereRaw('end_reservation < ?', [$endDate]);
                        });
                })
                ->where('status', "!=", "Canceled")
                ->selectRaw("room_id,capacity_room,price_room,quantity, SUM(`number`) AS number")
                ->groupBy('quantity', 'capacity_room', 'room_id', 'price_room')
                ->first();
            $find = DB::table('room')->where([
                ['hotel_id', $request->hotel_id], ['capacity_room', $room['capacity_room']]
            ])->first();
            if ($notAvailableRooms == null && $find == null) {
                return response()->json([
                    "status" => 0,
                    "message" => "capacity of room  " . $room['capacity_room'] . " not found in this hotel",
                    "price" => 0
                ]);
            }
            if (($notAvailableRooms != null && $notAvailableRooms->quantity - $notAvailableRooms->number < $room['number_of_room']) || ($find->quantity  < $room['number_of_room'])) {
                return response()->json([
                    "status" => 0,
                    "message" => "number of room not found",
                    "price" => 0
                ]);
            }
            $price_all_reserve = $price_all_reserve +  $find->price_room * $room['number_of_room'];
        }
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "price" => $price_all_reserve
        ]);
    }
    public function touristReserve(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "hotel_id" => "required|integer",
                "start_reservation" => "required",
                "end_reservation" => "required",                'rooms.*.capacity_room' => 'integer',
                'rooms.*.number_of_room' => 'integer'
            ]
        );
        $find = hotel::find($request->hotel_id);
        if ($find == null) {
            return response()->json([
                "status" => 0,
                "message" => "hotel not found",
            ]);
        }
        $startDate = $request->start_reservation;
        $endDate = $request->end_reservation;
        $price_all_reserve = 0;
        foreach ($request->rooms as $room) {
            $capacity = $room['capacity_room'];
            if ($room['number_of_room'] == 0) {
                continue;
            }
            $notAvailableRooms = DB::table('hotel')
                ->join('room', 'hotel.id', '=', 'room.hotel_id')
                ->Join('reserve_has_room', 'room.id', '=', 'reserve_has_room.room_id')
                ->join('reserve', 'reserve_has_room.reserve_id', '=', 'reserve.id')
                ->where('capacity_room', $capacity)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereRaw('start_reservation < ?', [$startDate])
                        ->whereRaw('end_reservation > ?', [$endDate])
                        ->orWhere(function ($subQuery)  use ($startDate, $endDate) {
                            $subQuery->whereRaw('start_reservation > ?', [$startDate])
                                ->whereRaw('start_reservation <?', [$endDate]);
                        })
                        ->orWhere(function ($subQuery)  use ($startDate, $endDate) {
                            $subQuery->whereRaw('end_reservation > ?', [$startDate])
                                ->whereRaw('end_reservation < ?', [$endDate]);
                        })
                        ->orWhere(function ($subQuery)  use ($startDate, $endDate) {
                            $subQuery->whereRaw('start_reservation > ?', [$startDate])
                                ->whereRaw('end_reservation < ?', [$endDate]);
                        });
                })
                ->where('status', "!=", "Canceled")
                ->selectRaw("room_id,capacity_room,price_room,quantity, SUM(`number`) AS number")
                ->groupBy('quantity', 'capacity_room', 'room_id', 'price_room')
                ->first();
            $find = DB::table('room')->where([
                ['hotel_id', $request->hotel_id], ['capacity_room', $room['capacity_room']]
            ])->first();
            if ($notAvailableRooms == null && $find == null) {
                return response()->json([
                    "status" => 0,
                    "message" => "capacity of room  " . $room['capacity_room'] . " not found in this hotel"
                ]);
            }
            if (($notAvailableRooms != null && $notAvailableRooms->quantity - $notAvailableRooms->number < $room['number_of_room']) || ($find->quantity  < $room['number_of_room'])) {
                return response()->json([
                    "status" => 0,
                    "message" => "number of room not found",
                ]);
            }
            $price_all_reserve = $price_all_reserve +  $find->price_room * $room['number_of_room'];
        }
        $reserve = [
            "start_reservation" => $request->start_reservation,
            "end_reservation" => $request->end_reservation,
            "tourist_id" => (DB::table('tourist')->where('user_id', auth()->user()->id)->first())->id,
            "status" => "Pending",
            "price_all_reserve" => $price_all_reserve
        ];
        $create = reserve::create($reserve);
        foreach ($request->rooms as $room) {
            if ($room['number_of_room'] == 0) {
                continue;
            }
            $reserve_has_room = [
                'reserve_id' => $create->id, 'number' => $room['number_of_room'], 'room_id' => $find->id
            ];
            reserve_has_room::create($reserve_has_room);
        }
        return response()->json([
            "status" => 1,
            "message" => "you are reserve",
        ]);
    }
    public function touristGetReserved()
    {
        auth()->user();
        $reservequery = reserve::query();
        $reservequery->where('tourist_id', (DB::table('tourist')->where('user_id', auth()->user()->id)->first())->id);
        $reserves = $reservequery->with(['reserve_has_room.room'])->get();
        $reservesReturn = [];
        foreach ($reserves as $reserve) {
            $rooms = [];
            $hotel_id = 0;
            foreach ($reserve->reserve_has_room as $reserve_has_room) {
                $rooms[] = [
                    'capacity_room' => $reserve_has_room->room->capacity_room,
                    'number' => $reserve_has_room->number,
                ];
                $hotel_id = $reserve_has_room->room->hotel_id;
            }
            $reservesReturn[] = [
                'id' => $reserve->id,
                'hotel_id' => $hotel_id,
                'status' => $reserve->status,
                'price_all_reserve' => $reserve->price_all_reserve,
                'start_reservation' => $reserve->start_reservation,
                'end_reservation' => $reserve->end_reservation,
                "rooms" => $rooms
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $reservesReturn
        ]);
    }
    // TODO: 
    public function touristUpdateReserved(Request $request)
    {
        auth()->user();
        return response()->json([
            "status" => 0,
            "message" => "noooooooooooooooo",
        ]);
    }
    public function touristDeleteReserved($id)
    {
        auth()->user();
        $find = reserve::find($id);
        if ($find == null) {
            return response()->json([
                "status" => 0,
                "message" => "reserve not found",
            ]);
        }
        $update = DB::table('reserve_has_room')->where('reserve_id', $id)->delete();
        $update = DB::table('reserve')->where('id', $id)->delete();
        return response()->json([
            "status" => 1,
            "message" => "reserve was deleted",
        ]);
    }
    public function adminUpdateReserved(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "id" => "required|integer",
                "status" => "required",
            ]
        );
        $find = reserve::find($request->id);
        if ($find == null) {
            return response()->json([
                "status" => 0,
                "message" => "reserve not found",
            ]);
        }
        if ($find['status'] == "Submitted" | $find['status'] == "Canceled") {
            return response()->json([
                "status" => 0,
                "message" => "this request was handler"
            ]);
        }
        if ($request->status != "Submitted" && $request->status != "Canceled") {
            return response()->json([
                "status" => 0,
                "message" => "your post payment status not found in system"
            ]);
        }
        if ($request->status == "Submitted") {
            $oldWallet = DB::table('tourist')->select('wallet')->where('id', $find->tourist_id)->first();
            $newwallet = $oldWallet->wallet - $find->price_all_reserve;
            if ($newwallet >= 0) {
                $updateWallet = tourist::where('id', $find->tourist_id)->update(array('wallet' =>  $newwallet));
            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "your  wallet less than price trip"
                ]);
            }
        }
        $update = reserve::where('id', $request->id)->update(array('status' => $request->status));
        return response()->json([
            "status" => 1,
            "message" => "reserve was updated status",
        ]);
    }
    public function adminGetReserved()
    {
        auth()->user();
        $reservequery = reserve::query();
        $reserves = $reservequery->with(['reserve_has_room.room'])->get();
        $reservesReturn = [];
        foreach ($reserves as $reserve) {
            $tourist = tourist::find($reserve->tourist_id);
            $user = User::find($tourist->user_id);
            $rooms = [];
            $hotel_id = 0;
            foreach ($reserve->reserve_has_room as $reserve_has_room) {
                $rooms[] = [
                    'capacity_room' => $reserve_has_room->room->capacity_room,
                    'number' => $reserve_has_room->number,
                ];
                $hotel_id = $reserve_has_room->room->hotel_id;
            }
            $reservesReturn[] = [
                'id' => $reserve->id,
                'hotel_id' => $hotel_id,
                "Email_address" => $user->Email_address,
                "tourist_name" => $tourist->tourist_name,
                "wallet" => $tourist->wallet,
                'status' => $reserve->status,
                'price_all_reserve' => $reserve->price_all_reserve,
                'start_reservation' => $reserve->start_reservation,
                'end_reservation' => $reserve->end_reservation,
                "rooms" => $rooms
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "succes",
            "data" => $reservesReturn
        ]);
    }
}
