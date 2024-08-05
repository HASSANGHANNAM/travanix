<?php

namespace Database\Seeders;

use App\Models\tourist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class touristSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = ([
            "Email_address" => "nameaalbad@gmail.com",
            "password" => "11111111",
            "type" => 2
        ]);
        $user['password'] = Hash::make($user['password']);
        $user = User::create($user);
        $touristData = [
            'user_id' => $user['id'],
            'wallet' => 0,
            'tourist_name' => "nawwar"
        ];
        tourist::create($touristData);
        $accessToken = $user->createToken('authToken')->accessToken;
    }
}
