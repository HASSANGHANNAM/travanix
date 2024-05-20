<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class adminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = ([
            "Email_address" => "adminadmin@gmail.com",
            "password" => "admin12345",
            "type" => 1
        ]);
        $user['password'] = Hash::make($user['password']);
        $user = User::create($user);
        $accessToken = $user->createToken('authToken')->accessToken;
    }
}
