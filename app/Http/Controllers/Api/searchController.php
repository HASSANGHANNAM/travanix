<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\hotel;
use App\Models\resturant;
use App\Models\trip;
use App\Models\attraction_activity;
use App\Models\favorite;
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
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "trips" => $trips,
            "hotels" => $hotels,
            "resturants" => $resturants,
            "attraction_activities" => $attraction_activities,
        ]);
    }
    public function touristSearchHotel(Request $request)
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
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "hotels" => $hotels,
        ]);
    }
    public function touristSearchResturant(Request $request)
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
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "resturants" => $resturants,
        ]);
    }
    public function touristSearchattraction_activity(Request $request)
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
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "attraction_activities" => $attraction_activities,
        ]);
    }
    public function touristSearchTrip(Request $request)
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
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "trips" => $trips,
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
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "trips" => $trips,
            "hotels" => $hotels,
            "resturants" => $resturants,
            "attraction_activities" => $attraction_activities,
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
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "trips" => $trips,
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
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "attraction_activities" => $attraction_activities,
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
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "resturants" => $resturants,
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
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "hotels" => $hotels,
        ]);
    }
}



// $services = Service::with(['hotels' => function ($query) {
//     $query->select('hotel.id', 'hotel.name', 'hotel_service_relation.start_date', 'hotel_service_relation.end_date', 'hotel_service_relation.price', 'hotel_service_relation.quantity');
// }])->get();