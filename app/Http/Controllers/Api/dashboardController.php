<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\tourist;
use App\Models\trip;
use App\Models\User;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function adminDashboard()
    {
        auth()->user();
        $tourist = tourist::count();
        $trip2023 = trip::whereBetween('trip_start_time', ['2023-01-01', '2023-12-31'])->count();
        $trip2024 = trip::whereBetween('trip_start_time', ['2024-01-01', '2024-12-31'])->count();
        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "tourist" => $tourist,
            "number of trip in 2023" => $trip2023,
            "number of trip in 2024" => $trip2024
        ]);
    }
}
