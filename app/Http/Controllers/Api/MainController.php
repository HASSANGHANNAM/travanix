<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\tourist;

class MainController extends Controller
{
    public function touristRegester(Request $request)
    {
        $request->validate([
            "Email_address" =>   [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|hotmail\.com)$/u',
                'max:255',
                'unique:users'
            ],
            // "Phone_number" => "required|min: 10|max:45|unique:users|regex:/09(.+)/",
            "Password" => ['required', 'string', password::min(8)],
            "name" => "required|max:45"
        ]);
        $userData['type'] = 2;
        //$userData['Phone_number'] = $request->Phone_number;
        $userData['Email_address'] = $request->Email_address;
        $userData['password'] = Hash::make($request->Password);
        $userData = User::create($userData);
        $touristData = [
            'user_id' => $userData['id'],
            'wallet' => 0,
            'name' => $request->name
        ];
        tourist::create($touristData);
        $accessToken = $userData->createToken('auth_token')->plainTextToken;
        return response()->json([
            "status" => 1,
            "message" => "succes",
            'access_token' => $accessToken
        ]);
    }
    public function touristLogin(Request $request)
    {
        $request->validate(
            [
                "Email_address" =>   [
                    'required',
                    'email',
                    'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|hotmail\.com)$/u',
                    'max:255'
                ],
                "Password" => ['required', 'string', password::min(8)]
            ]
        );
        $loginData = [
            "Email_address" => $request->Email_address,
            "password" => $request->Password
        ];
        if (auth()->attempt($loginData)) {
            $accessToken = auth()->user()->createToken('auth_token')->plainTextToken;
            return response()->json([
                "status" => 1,
                "message" => "loged in",
                'access_token' => $accessToken
            ]);
        }
        $useremail = DB::table('users')->where('Email_address', $loginData['Email_address'])->first();
        if (isset($useremail)) {
            return response()->json([
                "status" => 0,
                "message" => "password uncorrect",
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "your email not found",
        ]);
    }
    public function adminLogin(Request $request)
    {
        $request->validate(
            [
                "Email_address" =>   [
                    'required',
                    'email',
                    'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|hotmail\.com)$/u',
                    'max:255',
                ],
                "Password" => ['required', 'string', password::min(8)]
            ]
        );
        $loginData = [
            "Email_address" => $request->Email_address,
            "password" => $request->Password
        ];
        if (auth()->attempt($loginData)) {
            $accessToken = auth()->user()->createToken('auth_token')->plainTextToken;
            return response()->json([
                "status" => 1,
                "message" => "loged in",
                'access_token' => $accessToken
            ]);
        }
        $useremail = DB::table('users')->where('Email_address', $loginData['Email_address'])->first();
        if (isset($useremail)) {
            return response()->json([
                "status" => 0,
                "message" => "password uncorrect",
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "your email not found",
        ]);
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" => 1,
            "message" => "loged out"
        ]);
    }
    public function touristCheckEmailPassword(Request $request)
    {
        $request->validate(
            [
                "Email_address" =>   [
                    'required',
                    'email',
                    'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|hotmail\.com)$/u',
                    'max:255',
                ]
            ]
        );
        $check = user::where('Email_address', $request->Email_address)->first();
        if (isset($check)) {
            $accessToken = $check->createToken('access_token')->plainTextToken;
            return response()->json([
                "status" => 1,
                "message" => "correct email",
                'access_token' => $accessToken
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "not found email",
        ]);
    }
    public function touristChangePassword(Request $request)
    {
        $request->validate([
            "Password" => ['required', 'string', password::min(8)]
        ]);
        $newpassword = Hash::make($request->Password);
        $u = User::where('id', auth()->user()->id)->update(array('password' => $newpassword));
        if ($u != 0) {
            return response()->json([
                "status" => 1,
                "message" => "changed password"
            ]);
        }
        return response()->json([
            "status" => 0,
            "message" => "not changed password"
        ]);
    }
}


// public function login(Request $request)
//     {
//         $request->validate(
//             [
//                 "Email_address_or_Phone_number" => "required",
//                 "Password" => ['required', 'string', password::min(8)]
//             ]
//         );
//         $loginDataPhone = [
//             "Phone_number" => $request->Email_address_or_Phone_number,
//             "password" => $request->Password
//         ];
//         $loginDataEmail = [
//             "Email_address" => $request->Email_address_or_Phone_number,
//             "password" => $request->Password
//         ];
//         if (auth()->attempt($loginDataPhone) || auth()->attempt($loginDataEmail)) {
//             $accessToken = auth()->user()->createToken('auth_token')->plainTextToken;
//             return response()->json([
//                 "status" => 1,
//                 "message" => "loged in",
//                 'access_token' => $accessToken
//             ]);
//         }
//         $userphone = DB::table('users')->where('Phone_number', $loginDataPhone['Phone_number'])->first();
//         $useremail = DB::table('users')->where('Email_address', $loginDataEmail['Email_address'])->first();
//         if (isset($userphone) || isset($useremail)) {
//             return response()->json([
//                 "status" => 0,
//                 "message" => "password uncorrect",
//             ]);
//         }
//         return response()->json([
//             "status" => 0,
//             "message" => "not found your email or phone number",
//         ]);
//     }