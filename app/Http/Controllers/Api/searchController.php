<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\hotel;
use App\Models\resturant;
use App\Models\trip;
use App\Models\attraction_activity;
use App\Models\favorite;
use App\Models\nation;
use App\Models\tourist_has_trip;
use App\Models\trip_has_place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class searchController extends Controller
{
    public function touristSearchAll(Request $request)
    {
        auth()->user();
        $tripquery = trip::query();
        $hotelquery = hotel::query();
        $resturantquery = resturant::query();
        $attraction_activityquery = attraction_activity::query();
        if ($request->has('name') & $request->input('name') != null) {
            $tripquery->where('trip_name', 'like', '%' . $request->input('name') . '%');
            $hotelquery->where('hotel_name', 'like', '%' . $request->input('name') . '%');
            $resturantquery->where('resturant_name', 'like', '%' . $request->input('name') . '%');
            $attraction_activityquery->where('attraction_activity_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('address') & $request->input('address') != null) {
            $tripquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
            $hotelquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
            $resturantquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
            $attraction_activityquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
        }
        if ($request->has('nation_name') & $request->input('nation_name') != null) {
            $tripquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
            $hotelquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
            $resturantquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
            $attraction_activityquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
        }
        if ($request->has('city_name') & $request->input('city_name') != null) {
            $tripquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
            $hotelquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
            $resturantquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
            $attraction_activityquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
        }
        if ($request->has('avg_rate') & $request->input('avg_rate') != null) {
            $tripquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
            $hotelquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
            $resturantquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
            $attraction_activityquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
        }
        $trips = $tripquery->with(['location', 'location.city', 'location.city.nation'])->get();
        $tripsReturn = [];
        foreach ($trips as $trip) {
            $fav = false;
            if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['trip_id', $trip->id]])->first()) {
                $fav = true;
            }
            $sum = tourist_has_trip::where('trip_id', $trip->id)->sum('number_of_seat');
            $number_of_seats_available = $trip->number_of_allSeat - $sum;
            $image = trip_has_place::where('trip_id', $trip->id)
                ->join('attraction_activities', 'trip_has_place.attraction_activity_id', '=', 'attraction_activities.id')
                ->join('image', 'attraction_activities.id', '=', 'image.attraction_activity_id')
                ->select('image.path_of_image')
                ->first();
            $tripsReturn[] = [
                'id' => $trip->id,
                'type_of_trip' => $trip->type_of_trip,
                'trip_name' => $trip->trip_name,
                'description' => $trip->description,
                'price_trip' => $trip->price_trip,
                'number_of_allSeat' => $trip->number_of_allSeat,
                'trip_start_time' => $trip->trip_start_time,
                'trip_end_time' => $trip->trip_end_time,
                'address' => $trip->location->address,
                'coordinate_y' => $trip->location->coordinate_y,
                'coordinate_x' => $trip->location->coordinate_x,
                "city_id" => $trip->location->city->id,
                'city_name' => $trip->location->city->city_name,
                "nation_id" => $trip->location->city->nation->id,
                'nation_name' => $trip->location->city->nation->nation_name,
                "image" => $image->path_of_image,
                'places' => $trip->places->map(function ($place) {
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
        $hotels = $hotelquery->with(['location', 'location.city', 'location.city.nation', 'hotel_has_services.service'])->get();
        $hotelsReturn = [];
        foreach ($hotels as $hotel) {
            $services = [];
            $fav = false;
            if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['hotel_id', $hotel->id]])->first()) {
                $fav = true;
            }
            foreach ($hotel->hotel_has_services as $h)
                $services[] = $h->service->service;
            $hotelsReturn[] = [
                'id' => $hotel->id,
                'address' => $hotel->location->address,
                'coordinate_y' => $hotel->location->coordinate_y,
                'coordinate_x' => $hotel->location->coordinate_x,
                'city_name' => $hotel->location->city->city_name,
                'nation_name' => $hotel->location->city->nation->nation_name,
                'simple_description_about_hotel' => $hotel->simple_description_about_hotel,
                'hotel_name' => $hotel->hotel_name,
                'hotel_class' => $hotel->hotel_class,
                'phone_number' => $hotel->phone_number,
                'images' => $hotel->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "services" => $services,
                "favorite" => $fav
            ];
        }
        $resturants = $resturantquery->with(['location', 'location.city', 'location.city.nation'])->get();
        $resturantsReturn = [];
        foreach ($resturants as $resturant) {
            $fav = false;
            if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['resturant_id', $resturant->id]])->first()) {
                $fav = true;
            }
            $resturantsReturn[] = [
                'id' => $resturant->id,
                'address' => $resturant->location->address,
                'coordinate_y' => $resturant->location->coordinate_y,
                'coordinate_x' => $resturant->location->coordinate_x,
                'city_name' => $resturant->location->city->city_name,
                'nation_name' => $resturant->location->city->nation->nation_name,
                'type_of_food' => $resturant->type_of_food,
                'descreption' => $resturant->descreption,
                'resturant_name' => $resturant->resturant_name,
                'resturant_class' => $resturant->resturant_class,
                'phone_number' => $resturant->phone_number,
                'opining_time' => $resturant->opining_time,
                'closing_time' => $resturant->closing_time,
                'images' => $resturant->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "favorite" => $fav
            ];
        }
        $attraction_activities = $attraction_activityquery->with(['location', 'location.city', 'location.city.nation'])->get();
        $attraction_activitiesReturn = [];
        foreach ($attraction_activities as $attraction_activity) {
            $fav = false;
            if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['resturant_id', $attraction_activity->id]])->first()) {
                $fav = true;
            }
            $attraction_activitiesReturn[] = [
                'id' => $attraction_activity->id,
                'address' => $attraction_activity->location->address,
                'coordinate_y' => $attraction_activity->location->coordinate_y,
                'coordinate_x' => $attraction_activity->location->coordinate_x,
                'city_name' => $attraction_activity->location->city->city_name,
                'nation_name' => $attraction_activity->location->city->nation->nation_name,
                'attraction_activity_name' => $attraction_activity->attraction_activity_name,
                'description' => $attraction_activity->description,
                'opening_time' => $attraction_activity->opening_time,
                'closing_time' => $attraction_activity->closing_time,
                'images' => $attraction_activity->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "favorite" => $fav
            ];
        }
        if ($request->input('avg_rate') == null & $request->input('city_name') == null & $request->input('nation_name') == null & $request->input('address') == null & $request->input('name') == null) {
            return response()->json([
                "status" => 1,
                "message" => "succes",
                "trips" => [],
                "hotels" => [],
                "resturants" => [],
                "attraction_activities" => [],
            ]);
        }
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "trips" => $tripsReturn,
            "hotels" => $hotelsReturn,
            "resturants" => $resturantsReturn,
            "attraction_activities" => $attraction_activitiesReturn,
        ]);
    }
    public function touristSearchHotel(Request $request)
    {
        auth()->user();
        $hotelquery = hotel::query();
        if ($request->has('name') & $request->input('name') != null) {
            $hotelquery->where('hotel_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('hotel_class') & $request->input('hotel_class') != null) {
            $hotelquery->where('hotel_class', '=', $request->input('hotel_class'));
        }
        if ($request->has('address') & $request->input('address') != null) {
            $hotelquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
        }
        if ($request->has('nation_name') & $request->input('nation_name') != null) {
            $hotelquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
        }
        if ($request->has('city_name') & $request->input('city_name') != null) {
            $hotelquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
        }
        if ($request->has('avg_rate') & $request->input('avg_rate') != null) {
            $hotelquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
        }
        $hotels = $hotelquery->with(['location', 'location.city', 'location.city.nation', 'hotel_has_services.service'])->get();
        $hotelsReturn = [];
        foreach ($hotels as $hotel) {
            $services = [];
            $fav = false;
            if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['hotel_id', $hotel->id]])->first()) {
                $fav = true;
            }
            foreach ($hotel->hotel_has_services as $h)
                $services[] = $h->service->service;
            $hotelsReturn[] = [
                'id' => $hotel->id,
                'address' => $hotel->location->address,
                'coordinate_y' => $hotel->location->coordinate_y,
                'coordinate_x' => $hotel->location->coordinate_x,
                'city_name' => $hotel->location->city->city_name,
                'nation_name' => $hotel->location->city->nation->nation_name,
                'simple_description_about_hotel' => $hotel->simple_description_about_hotel,
                'hotel_name' => $hotel->hotel_name,
                'hotel_class' => $hotel->hotel_class,
                'phone_number' => $hotel->phone_number,
                'images' => $hotel->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "services" => $services,
                "favorite" => $fav
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "hotels" => $hotelsReturn,
        ]);
    }
    public function touristSearchResturant(Request $request)
    {
        auth()->user();
        $resturantquery = resturant::query();
        if ($request->has('name') & $request->input('name') != null) {
            $resturantquery->where('resturant_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('resturant_class') & $request->input('resturant_class') != null) {
            $resturantquery->where('resturant_class', '=', $request->input('resturant_class'));
        }
        if ($request->has('type_of_food') & $request->input('type_of_food') != null) {
            $resturantquery->where('type_of_food', 'like', '%' . $request->input('type_of_food') . '%');
        }
        if ($request->has('time') & $request->input('time') != null) {
            $resturantquery->where('closing_time', ">=", $request->input('time'))->where('opining_time', "<=", $request->input('time'));
        }
        if ($request->has('address') & $request->input('address') != null) {
            $resturantquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
        }
        if ($request->has('nation_name') & $request->input('nation_name') != null) {
            $resturantquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
        }
        if ($request->has('city_name') & $request->input('city_name') != null) {
            $resturantquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
        }
        if ($request->has('avg_rate') & $request->input('avg_rate') != null) {
            $resturantquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
        }
        $resturants = $resturantquery->with(['location', 'location.city', 'location.city.nation'])->get();
        $resturantsReturn = [];
        foreach ($resturants as $resturant) {
            $fav = false;
            if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['resturant_id', $resturant->id]])->first()) {
                $fav = true;
            }
            $resturantsReturn[] = [
                'id' => $resturant->id,
                'address' => $resturant->location->address,
                'coordinate_y' => $resturant->location->coordinate_y,
                'coordinate_x' => $resturant->location->coordinate_x,
                'city_name' => $resturant->location->city->city_name,
                'nation_name' => $resturant->location->city->nation->nation_name,
                'type_of_food' => $resturant->type_of_food,
                'descreption' => $resturant->descreption,
                'resturant_name' => $resturant->resturant_name,
                'resturant_class' => $resturant->resturant_class,
                'phone_number' => $resturant->phone_number,
                'opining_time' => $resturant->opining_time,
                'closing_time' => $resturant->closing_time,
                'images' => $resturant->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "favorite" => $fav
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "resturants" => $resturantsReturn,
        ]);
    }
    public function touristSearchattraction_activity(Request $request)
    {
        auth()->user();
        $attraction_activityquery = attraction_activity::query();
        if ($request->has('name') & $request->input('name') != null) {
            $attraction_activityquery->where('attraction_activity_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('address') & $request->input('address') != null) {
            $attraction_activityquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
        }
        if ($request->has('time') & $request->input('time') != null) {
            $attraction_activityquery->where('closing_time', ">=", $request->input('time'))->where('opening_time', "<=", $request->input('time'));
        }
        if ($request->has('nation_name') & $request->input('nation_name') != null) {
            $attraction_activityquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
        }
        if ($request->has('city_name') & $request->input('city_name') != null) {
            $attraction_activityquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
        }
        if ($request->has('avg_rate') & $request->input('avg_rate') != null) {
            $attraction_activityquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
        }
        $attraction_activities = $attraction_activityquery->with(['location', 'location.city', 'location.city.nation'])->get();
        $attraction_activitiesReturn = [];
        foreach ($attraction_activities as $attraction_activity) {
            $fav = false;
            if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['resturant_id', $attraction_activity->id]])->first()) {
                $fav = true;
            }
            $attraction_activitiesReturn[] = [
                'id' => $attraction_activity->id,
                'address' => $attraction_activity->location->address,
                'coordinate_y' => $attraction_activity->location->coordinate_y,
                'coordinate_x' => $attraction_activity->location->coordinate_x,
                'city_name' => $attraction_activity->location->city->city_name,
                'nation_name' => $attraction_activity->location->city->nation->nation_name,
                'attraction_activity_name' => $attraction_activity->attraction_activity_name,
                'description' => $attraction_activity->description,
                'opening_time' => $attraction_activity->opening_time,
                'closing_time' => $attraction_activity->closing_time,
                'images' => $attraction_activity->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "favorite" => $fav
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "attraction_activities" => $attraction_activitiesReturn,
        ]);
    }
    public function touristSearchTrip(Request $request)
    {
        auth()->user();
        $tripquery = trip::query();

        if ($request->has('name') & $request->input('name') != null) {
            $tripquery->where('trip_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('type_of_trip') & $request->input('type_of_trip') != null) {
            $tripquery->where('type_of_trip', '=', $request->input('type_of_trip'));
        }
        if ($request->has('price_trip') & $request->input('price_trip') != null) {
            $tripquery->where('price_trip', '=', $request->input('price_trip'));
        }
        if ($request->has('address') & $request->input('address') != null) {
            $tripquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
        }
        if ($request->has('nation_name') & $request->input('nation_name') != null) {
            $tripquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
        }
        if ($request->has('city_name') & $request->input('city_name') != null) {
            $tripquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
        }
        if ($request->has('avg_rate') & $request->input('avg_rate') != null) {
            $tripquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
        }
        $trips = $tripquery->with(['location', 'location.city', 'location.city.nation'])->get();
        $tripsReturn = [];
        foreach ($trips as $trip) {
            $fav = false;
            if (DB::table('favorite')->where([['tourist_id', DB::table('tourist')->where('user_id', auth()->user()->id)->first()->id], ['trip_id', $trip->id]])->first()) {
                $fav = true;
            }
            $sum = tourist_has_trip::where('trip_id', $trip->id)->sum('number_of_seat');
            $number_of_seats_available = $trip->number_of_allSeat - $sum;
            $image = trip_has_place::where('trip_id', $trip->id)
                ->join('attraction_activities', 'trip_has_place.attraction_activity_id', '=', 'attraction_activities.id')
                ->join('image', 'attraction_activities.id', '=', 'image.attraction_activity_id')
                ->select('image.path_of_image')
                ->first();
            $tripsReturn[] = [
                'id' => $trip->id,
                'type_of_trip' => $trip->type_of_trip,
                'trip_name' => $trip->trip_name,
                'description' => $trip->description,
                'price_trip' => $trip->price_trip,
                'number_of_allSeat' => $trip->number_of_allSeat,
                'trip_start_time' => $trip->trip_start_time,
                'trip_end_time' => $trip->trip_end_time,
                'address' => $trip->location->address,
                'coordinate_y' => $trip->location->coordinate_y,
                'coordinate_x' => $trip->location->coordinate_x,
                "city_id" => $trip->location->city->id,
                'city_name' => $trip->location->city->city_name,
                "nation_id" => $trip->location->city->nation->id,
                'nation_name' => $trip->location->city->nation->nation_name,
                "image" => $image->path_of_image,
                'places' => $trip->places->map(function ($place) {
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
            "message" => "succes   ",
            "trips" => $tripsReturn,
        ]);
    }
    public function touristSearchAllFavorite(Request $request)
    {
        auth()->user();
        $tripfavoritequery = favorite::query();
        $tripfavoritequery->with(['hotel'])->where('tourist_id', (DB::table('tourist')->where('user_id', auth()->user()->id)->first())->id);
        $hotelfavoritequery = favorite::query();
        $resturantfavoritequery = favorite::query();
        $attraction_activityfavoritequery = favorite::query();
        // if ($request->has('name')) {
        //     $tripfavoritequery->whereHas('hotel', function ($nameQuery) use ($request) {
        //         $nameQuery->where('hotel_name', 'like', '%' . $request->input('name') . '%');
        //     });
        // }
        $favorites = $tripfavoritequery->get();
        dd($favorites);


        // if ($request->has('name')) {
        //     $tripquery->where('trip_name', 'like', '%' . $request->input('name') . '%');
        // }
        // if ($request->has('address')) {
        //     $tripquery->whereHas('location', function ($locationQuery) use ($request) {
        //         $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
        //     });
        // }
        // if ($request->has('nation_name')) {
        //     $tripquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
        //         $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
        //     });
        // }
        // if ($request->has('city_name')) {
        //     $tripquery->whereHas('location.city', function ($cityQuery) use ($request) {
        //         $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
        //     });
        // }
        // if ($request->has('avg_rate')) {
        //     $tripquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
        //         $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
        //     });
        // }
        // if ($request->has('name')) {
        //     $hotelquery->where('hotel_name', 'like', '%' . $request->input('name') . '%');
        // }
        // if ($request->has('address')) {
        //     $hotelquery->whereHas('location', function ($locationQuery) use ($request) {
        //         $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
        //     });
        // }
        // if ($request->has('nation_name')) {
        //     $hotelquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
        //         $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
        //     });
        // }
        // if ($request->has('city_name')) {
        //     $hotelquery->whereHas('location.city', function ($cityQuery) use ($request) {
        //         $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
        //     });
        // }
        // if ($request->has('avg_rate')) {
        //     $hotelquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
        //         $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
        //     });
        // }
        // if ($request->has('name')) {
        //     $resturantquery->where('resturant_name', 'like', '%' . $request->input('name') . '%');
        // }
        // if ($request->has('address')) {
        //     $resturantquery->whereHas('location', function ($locationQuery) use ($request) {
        //         $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
        //     });
        // }
        // if ($request->has('nation_name')) {
        //     $resturantquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
        //         $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
        //     });
        // }
        // if ($request->has('city_name')) {
        //     $resturantquery->whereHas('location.city', function ($cityQuery) use ($request) {
        //         $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
        //     });
        // }
        // if ($request->has('avg_rate')) {
        //     $resturantquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
        //         $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
        //     });
        // }
        // if ($request->has('name')) {
        //     $attraction_activityquery->where('attraction_activity_name', 'like', '%' . $request->input('name') . '%');
        // }
        // if ($request->has('address')) {
        //     $attraction_activityquery->whereHas('location', function ($locationQuery) use ($request) {
        //         $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
        //     });
        // }
        // if ($request->has('nation_name')) {
        //     $attraction_activityquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
        //         $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
        //     });
        // }
        // if ($request->has('city_name')) {
        //     $attraction_activityquery->whereHas('location.city', function ($cityQuery) use ($request) {
        //         $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
        //     });
        // }
        // if ($request->has('avg_rate')) {
        //     $attraction_activityquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
        //         $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
        //     });
        // }
        // $trips = $tripquery->with(['location', 'location.city', 'location.city.nation'])->get();
        // $hotels = $hotelquery->with(['location', 'location.city', 'location.city.nation', 'hotel_has_services.service'])->get();
        // $resturants = $resturantquery->with(['location', 'location.city', 'location.city.nation'])->get();
        // $attraction_activities = $attraction_activityquery->with(['location', 'location.city', 'location.city.nation'])->get();
        // return response()->json([
        //     "status" => 1,
        //     "message" => "succes   ",
        //     "trips" => $trips,
        //     "hotels" => $hotels,
        //     "resturants" => $resturants,
        //     "attraction_activities" => $attraction_activities,
        // ]);
    }
    public function adminSearchAll(Request $request)
    {
        auth()->user();
        $tripquery = trip::query();
        $hotelquery = hotel::query();
        $resturantquery = resturant::query();
        $attraction_activityquery = attraction_activity::query();
        if ($request->has('name')) {
            $tripquery->where('trip_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('address')) {
            $tripquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
        }
        if ($request->has('nation_name')) {
            $tripquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
        }
        if ($request->has('city_name')) {
            $tripquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
        }
        if ($request->has('avg_rate')) {
            $tripquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
        }
        if ($request->has('name')) {
            $hotelquery->where('hotel_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('address')) {
            $hotelquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
        }
        if ($request->has('nation_name')) {
            $hotelquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
        }
        if ($request->has('city_name')) {
            $hotelquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
        }
        if ($request->has('avg_rate')) {
            $hotelquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
        }
        if ($request->has('name')) {
            $resturantquery->where('resturant_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('address')) {
            $resturantquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
        }
        if ($request->has('nation_name')) {
            $resturantquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
        }
        if ($request->has('city_name')) {
            $resturantquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
        }
        if ($request->has('avg_rate')) {
            $resturantquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
        }
        if ($request->has('name')) {
            $attraction_activityquery->where('attraction_activity_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('address')) {
            $attraction_activityquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
        }
        if ($request->has('nation_name')) {
            $attraction_activityquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
        }
        if ($request->has('city_name')) {
            $attraction_activityquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
        }
        if ($request->has('avg_rate')) {
            $attraction_activityquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
        }
        $trips = $tripquery->with(['location', 'location.city', 'location.city.nation'])->get();
        $hotels = $hotelquery->with(['location', 'location.city', 'location.city.nation', 'hotel_has_services.service'])->get();
        $resturants = $resturantquery->with(['location', 'location.city', 'location.city.nation'])->get();
        $attraction_activities = $attraction_activityquery->with(['location', 'location.city', 'location.city.nation'])->get();
        $attraction_activitiesReturn = [];
        foreach ($attraction_activities as $attraction_activity) {
            $attraction_activitiesReturn[] = [
                'id' => $attraction_activity->id,
                'address' => $attraction_activity->location->address,
                'coordinate_y' => $attraction_activity->location->coordinate_y,
                'coordinate_x' => $attraction_activity->location->coordinate_x,
                'city_name' => $attraction_activity->location->city->city_name,
                'nation_name' => $attraction_activity->location->city->nation->nation_name,
                'attraction_activity_name' => $attraction_activity->attraction_activity_name,
                'description' => $attraction_activity->description,
                'opening_time' => $attraction_activity->opening_time,
                'closing_time' => $attraction_activity->closing_time,
                'images' => $attraction_activity->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
            ];
        }
        $resturantsReturn = [];
        foreach ($resturants as $resturant) {
            $resturantsReturn[] = [
                'id' => $resturant->id,
                'address' => $resturant->location->address,
                'coordinate_y' => $resturant->location->coordinate_y,
                'coordinate_x' => $resturant->location->coordinate_x,
                'city_name' => $resturant->location->city->city_name,
                'nation_name' => $resturant->location->city->nation->nation_name,
                'type_of_food' => $resturant->type_of_food,
                'descreption' => $resturant->descreption,
                'resturant_name' => $resturant->resturant_name,
                'resturant_class' => $resturant->resturant_class,
                'phone_number' => $resturant->phone_number,
                'opining_time' => $resturant->opining_time,
                'closing_time' => $resturant->closing_time,
                'images' => $resturant->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
            ];
        }
        $hotelsReturn = [];
        foreach ($hotels as $hotel) {
            $services = [];
            foreach ($hotel->hotel_has_services as $h)
                $services[] = $h->service->service;
            $hotelsReturn[] = [
                'id' => $hotel->id,
                'address' => $hotel->location->address,
                'coordinate_y' => $hotel->location->coordinate_y,
                'coordinate_x' => $hotel->location->coordinate_x,
                'city_name' => $hotel->location->city->city_name,
                'nation_name' => $hotel->location->city->nation->nation_name,
                'simple_description_about_hotel' => $hotel->simple_description_about_hotel,
                'hotel_name' => $hotel->hotel_name,
                'hotel_class' => $hotel->hotel_class,
                'phone_number' => $hotel->phone_number,
                'images' => $hotel->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "services" => $services,
            ];
        }
        $tripsReturn = [];
        foreach ($trips as $trip) {
            $sum = tourist_has_trip::where('trip_id', $trip->id)->sum('number_of_seat');
            $number_of_seats_available = $trip->number_of_allSeat - $sum;
            $image = trip_has_place::where('trip_id', $trip->id)
                ->join('attraction_activities', 'trip_has_place.attraction_activity_id', '=', 'attraction_activities.id')
                ->join('image', 'attraction_activities.id', '=', 'image.attraction_activity_id')
                ->select('image.path_of_image')
                ->first();
            $tripsReturn[] = [
                'id' => $trip->id,
                'type_of_trip' => $trip->type_of_trip,
                'trip_name' => $trip->trip_name,
                'description' => $trip->description,
                'price_trip' => $trip->price_trip,
                'number_of_allSeat' => $trip->number_of_allSeat,
                'trip_start_time' => $trip->trip_start_time,
                'trip_end_time' => $trip->trip_end_time,
                'address' => $trip->location->address,
                'coordinate_y' => $trip->location->coordinate_y,
                'coordinate_x' => $trip->location->coordinate_x,
                "city_id" => $trip->location->city->id,
                'city_name' => $trip->location->city->city_name,
                "nation_id" => $trip->location->city->nation->id,
                'nation_name' => $trip->location->city->nation->nation_name,
                "image" => $image->path_of_image,
                'places' => $trip->places->map(function ($place) {
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
            "message" => "succes   ",
            "trips" => $tripsReturn,
            "hotels" => $hotelsReturn,
            "resturants" => $resturantsReturn,
            "attraction_activities" => $attraction_activitiesReturn,
        ]);
    }
    public function adminSearchTrip(Request $request)
    {
        auth()->user();
        $tripquery = trip::query();

        if ($request->has('name')) {
            $tripquery->where('trip_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('type_of_trip')) {
            $tripquery->where('type_of_trip', '=', $request->input('type_of_trip'));
        }
        if ($request->has('price_trip')) {
            $tripquery->where('price_trip', '=', $request->input('price_trip'));
        }
        if ($request->has('address')) {
            $tripquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
        }
        if ($request->has('nation_name')) {
            $tripquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
        }
        if ($request->has('city_name')) {
            $tripquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
        }
        if ($request->has('avg_rate')) {
            $tripquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
        }
        $trips = $tripquery->with(['location', 'location.city', 'location.city.nation'])->get();
        $tripsReturn = [];
        foreach ($trips as $trip) {
            $sum = tourist_has_trip::where('trip_id', $trip->id)->sum('number_of_seat');
            $number_of_seats_available = $trip->number_of_allSeat - $sum;
            $image = trip_has_place::where('trip_id', $trip->id)
                ->join('attraction_activities', 'trip_has_place.attraction_activity_id', '=', 'attraction_activities.id')
                ->join('image', 'attraction_activities.id', '=', 'image.attraction_activity_id')
                ->select('image.path_of_image')
                ->first();
            $tripsReturn[] = [
                'id' => $trip->id,
                'type_of_trip' => $trip->type_of_trip,
                'trip_name' => $trip->trip_name,
                'description' => $trip->description,
                'price_trip' => $trip->price_trip,
                'number_of_allSeat' => $trip->number_of_allSeat,
                'trip_start_time' => $trip->trip_start_time,
                'trip_end_time' => $trip->trip_end_time,
                'address' => $trip->location->address,
                'coordinate_y' => $trip->location->coordinate_y,
                'coordinate_x' => $trip->location->coordinate_x,
                "city_id" => $trip->location->city->id,
                'city_name' => $trip->location->city->city_name,
                "nation_id" => $trip->location->city->nation->id,
                'nation_name' => $trip->location->city->nation->nation_name,
                "image" => $image->path_of_image,
                'places' => $trip->places->map(function ($place) {
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
            "message" => "succes   ",
            "trips" => $tripsReturn,
        ]);
    }
    public function adminSearchattraction_activity(Request $request)
    {
        auth()->user();
        $attraction_activityquery = attraction_activity::query();
        if ($request->has('name')) {
            $attraction_activityquery->where('attraction_activity_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('address')) {
            $attraction_activityquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
        }
        if ($request->has('time')) {
            $attraction_activityquery->where('closing_time', ">=", $request->input('time'))->where('opening_time', "<=", $request->input('time'));
        }
        if ($request->has('nation_name')) {
            $attraction_activityquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
        }
        if ($request->has('city_name')) {
            $attraction_activityquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
        }
        if ($request->has('avg_rate')) {
            $attraction_activityquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
        }
        $attraction_activities = $attraction_activityquery->with(['location', 'location.city', 'location.city.nation'])->get();
        $attraction_activitiesReturn = [];
        foreach ($attraction_activities as $attraction_activity) {
            $attraction_activitiesReturn[] = [
                'id' => $attraction_activity->id,
                'address' => $attraction_activity->location->address,
                'coordinate_y' => $attraction_activity->location->coordinate_y,
                'coordinate_x' => $attraction_activity->location->coordinate_x,
                'city_name' => $attraction_activity->location->city->city_name,
                'nation_name' => $attraction_activity->location->city->nation->nation_name,
                'attraction_activity_name' => $attraction_activity->attraction_activity_name,
                'description' => $attraction_activity->description,
                'opening_time' => $attraction_activity->opening_time,
                'closing_time' => $attraction_activity->closing_time,
                'images' => $attraction_activity->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "attraction_activities" => $attraction_activitiesReturn,
        ]);
    }
    public function adminSearchResturant(Request $request)
    {
        auth()->user();
        $resturantquery = resturant::query();
        if ($request->has('name')) {
            $resturantquery->where('resturant_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('resturant_class')) {
            $resturantquery->where('resturant_class', '=', $request->input('resturant_class'));
        }
        if ($request->has('type_of_food')) {
            $resturantquery->where('type_of_food', 'like', '%' . $request->input('type_of_food') . '%');
        }
        if ($request->has('time')) {
            $resturantquery->where('closing_time', ">=", $request->input('time'))->where('opining_time', "<=", $request->input('time'));
        }
        if ($request->has('address')) {
            $resturantquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
        }
        if ($request->has('nation_name')) {
            $resturantquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
        }
        if ($request->has('city_name')) {
            $resturantquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
        }
        if ($request->has('avg_rate')) {
            $resturantquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
        }
        $resturants = $resturantquery->with(['location', 'location.city', 'location.city.nation'])->get();
        $resturantsReturn = [];
        foreach ($resturants as $resturant) {
            $resturantsReturn[] = [
                'id' => $resturant->id,
                'address' => $resturant->location->address,
                'coordinate_y' => $resturant->location->coordinate_y,
                'coordinate_x' => $resturant->location->coordinate_x,
                'city_name' => $resturant->location->city->city_name,
                'nation_name' => $resturant->location->city->nation->nation_name,
                'type_of_food' => $resturant->type_of_food,
                'descreption' => $resturant->descreption,
                'resturant_name' => $resturant->resturant_name,
                'resturant_class' => $resturant->resturant_class,
                'phone_number' => $resturant->phone_number,
                'opining_time' => $resturant->opining_time,
                'closing_time' => $resturant->closing_time,
                'images' => $resturant->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "resturants" => $resturantsReturn,
        ]);
    }
    public function adminSearchHotel(Request $request)
    {
        auth()->user();
        $hotelquery = hotel::query();
        if ($request->has('name')) {
            $hotelquery->where('hotel_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('hotel_class')) {
            $hotelquery->where('hotel_class', '=', $request->input('hotel_class'));
        }
        if ($request->has('address')) {
            $hotelquery->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery->where('address', 'like', '%' . $request->input('address') . '%');
            });
        }
        if ($request->has('nation_name')) {
            $hotelquery->whereHas('location.city.nation', function ($nationQuery) use ($request) {
                $nationQuery->where('nation_name', 'like', '%' . $request->input('nation_name') . '%');
            });
        }
        if ($request->has('city_name')) {
            $hotelquery->whereHas('location.city', function ($cityQuery) use ($request) {
                $cityQuery->where('city_name', 'like', '%' . $request->input('city_name') . '%');
            });
        }
        if ($request->has('avg_rate')) {
            $hotelquery->whereHas('avg_rate', function ($avg_rateQuery) use ($request) {
                $avg_rateQuery->where('avg', '=', $request->input('avg_rate'));
            });
        }
        $hotels = $hotelquery->with(['location', 'location.city', 'location.city.nation', 'hotel_has_services.service'])->get();
        $hotelsReturn = [];
        foreach ($hotels as $hotel) {
            $services = [];
            foreach ($hotel->hotel_has_services as $h)
                $services[] = $h->service->service;
            $hotelsReturn[] = [
                'id' => $hotel->id,
                'address' => $hotel->location->address,
                'coordinate_y' => $hotel->location->coordinate_y,
                'coordinate_x' => $hotel->location->coordinate_x,
                'city_name' => $hotel->location->city->city_name,
                'nation_name' => $hotel->location->city->nation->nation_name,
                'simple_description_about_hotel' => $hotel->simple_description_about_hotel,
                'hotel_name' => $hotel->hotel_name,
                'hotel_class' => $hotel->hotel_class,
                'phone_number' => $hotel->phone_number,
                'images' => $hotel->images->map(function ($image) {
                    return $image->path_of_image;
                })->all(),
                "services" => $services,
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "hotels" => $hotelsReturn,
        ]);
    }
}



// $services = Service::with(['hotels' => function ($query) {
//     $query->select('hotel.id', 'hotel.name', 'hotel_service_relation.start_date', 'hotel_service_relation.end_date', 'hotel_service_relation.price', 'hotel_service_relation.quantity');
// }])->get();