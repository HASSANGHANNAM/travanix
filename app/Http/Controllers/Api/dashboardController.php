<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\charge_wallet;
use App\Models\reserve;
use App\Models\tourist;
use App\Models\tourist_has_trip;
use App\Models\trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class dashboardController extends Controller
{
    public function adminDashboard()
    {
        auth()->user();
        $tourist = tourist::count();
        $currentYear = Carbon::now('Asia/Damascus')->year;
        $previousYear = $currentYear - 1;
        $trip = trip::selectRaw('
        COALESCE(COUNT(CASE WHEN YEAR(updated_at) = ? THEN 1 END), 0) as current_year_total_seat,
        COALESCE(COUNT(CASE WHEN YEAR(updated_at) = ? THEN 1 END), 0) as previous_year_total_seat,
        YEAR(updated_at) as year', [$currentYear, $previousYear,])
            ->whereYear('updated_at', $currentYear)
            ->orWhereYear('updated_at', $previousYear)
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();
        $chargewallet = [];
        for ($month = 1; $month <= 12; $month++) {
            $chargewalletcuryear = DB::table('charge_wallet')
                ->where('status', 2)
                ->whereYear('updated_at', $currentYear)
                ->whereMonth('updated_at', $month)
                ->count();
            $chargewalletpreyear = DB::table('charge_wallet')
                ->where('status', 2)
                ->whereYear('updated_at', $previousYear)
                ->whereMonth('updated_at', $month)
                ->count();
            $chargewallet[] = [
                "year" => $previousYear,
                "month" => str_pad($month, 2, '0', STR_PAD_LEFT),
                "total" => $chargewalletpreyear ?: 0
            ];
            $chargewallet[] = [
                "year" => $currentYear,
                "month" => str_pad($month, 2, '0', STR_PAD_LEFT),
                "total" => $chargewalletcuryear ?: 0
            ];
        }
        $total_seat = tourist_has_trip::selectRaw('
            COALESCE(SUM(CASE WHEN YEAR(updated_at) = ? THEN number_of_seat ELSE 0 END), 0) as current_year_total_seat,
            COALESCE(SUM(CASE WHEN YEAR(updated_at) = ? THEN number_of_seat ELSE 0 END), 0) as previous_year_total_seat,
            YEAR(updated_at) as year
        ', [
            $currentYear,
            $previousYear,
        ])
            ->where('status', 'Submitted')
            ->whereYear('updated_at', $currentYear)
            ->orWhereYear('updated_at', $previousYear)
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();
        $hotelReserves = reserve::selectRaw('
        COALESCE(COUNT(CASE WHEN YEAR(start_reservation) = ? THEN 1 END), 0) as current_year_total_seat,
        COALESCE(COUNT(CASE WHEN YEAR(start_reservation) = ? THEN 1 END), 0) as previous_year_total_seat,
        YEAR(start_reservation) as year', [$currentYear, $previousYear,])
            ->where('status', "Submitted")
            ->whereYear('start_reservation', $currentYear)
            ->orWhereYear('start_reservation', $previousYear)
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();
        $data = [
            "number of tourist regester" => $tourist,
            "number of trip in 2 year" => $trip,
            "chargewallet" => $chargewallet,
            "total_seatreserve trip in 2 year" => $total_seat,
            "total_reserve hotel in 2 year" => $hotelReserves

        ];

        return response()->json([
            "status" => 1,
            "message" => "succes   ",
            "data" => $data
        ]);
    }
}
