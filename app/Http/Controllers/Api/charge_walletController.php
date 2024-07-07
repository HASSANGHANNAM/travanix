<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\charge_wallet;
use App\Models\tourist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class charge_walletController extends Controller
{
    public function touristChargeCode(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "charge_code" => "required|max:45",
            ]
        );
        $touristId = DB::table('tourist')->where('user_id', auth()->user()->id)->first();
        $chargeData = [
            'charge_code' => $request->charge_code,
            'tourist_id' => $touristId->id,
            'status' => 1
        ];
        $createCharge = charge_wallet::create($chargeData);
        return response()->json([
            "status" => 1,
            "message" => "charge send"
        ]);
    }
    public function adminGetAllCharges()
    {
        auth()->user();
        $charge_wallet = DB::table('charge_wallet')->select('id', 'tourist_id', 'charge_code', 'status')->get();
        $data = [];
        foreach ($charge_wallet as $cw) {
            $touristf = tourist::find($cw->tourist_id);
            $tourist = DB::table('users')->where('id', $touristf->user_id)->first();
            $data[] = [
                'id' => $cw->id,
                'charge_code' => $cw->charge_code,
                'status' =>  $cw->status,
                'tourist_id' => $cw->tourist_id,
                'tourist_Email_address' => $tourist->Email_address,
            ];
        }
        return response()->json([
            "status" => 1,
            "message" => " charges",
            "data" => $data
        ]);
    }
    public function adminCharge(Request $request)
    {
        auth()->user();
        $request->validate(
            [
                "id" => "required|integer",
                "status" => "required",
                "wallet" => "regex:/^\d*(\.\d{2})?$/",
            ]
        );
        $find = charge_wallet::find($request->id);
        if ($find['status'] != 1) {
            return response()->json([
                "status" => 0,
                "message" => "this request was handler"
            ]);
        }
        if ($request->status == false) {
            $update = charge_wallet::where('id', $request->id)->update(array('status' => 3));
            return response()->json([
                "status" => 1,
                "message" => "you refuse the code chrage"
            ]);
        }
        $request->validate(
            [
                "wallet" => "required",
            ]
        );
        $update = charge_wallet::where('id', $request->id)->update(array('status' => 2));
        $oldWallet = DB::table('tourist')->select('wallet')->where('id', $find->tourist_id)->first();
        $updateWallet = tourist::where('id', $find->tourist_id)->update(array('wallet' => ($request->wallet + $oldWallet->wallet)));
        return response()->json([
            "status" => 1,
            "message" => "you charge wallet"
        ]);
    }
}
