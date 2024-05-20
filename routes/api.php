<?php

use App\Http\Controllers\api\hotelController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MainController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/touristRegester', [MainController::class, 'touristRegester']);
Route::post('/touristLogin', [MainController::class, 'touristLogin']);
Route::post('/touristCheckEmailPassword', [MainController::class, 'touristCheckEmailPassword']);
Route::post('/Admin/adminLogin', [MainController::class, 'adminLogin']);


Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('/touristLogout', [MainController::class, 'logout']);
    Route::get('/Admin/adminLogout', [MainController::class, 'logout']);
    Route::post('/Admin/adminCreateHotel', [hotelController::class, 'adminCreateHotel']);
    Route::post('/Admin/adminCreateRooms', [hotelController::class, 'adminCreateRooms']);
    Route::get('/Admin/adminGetHotels', [hotelController::class, 'adminGetHotels']);
    Route::get('/touristProfile', [MainController::class, 'touristProfile']);
    Route::post('/touristUpdateProfile', [MainController::class, 'touristUpdateProfile']);
    Route::post('/touristChangePassword', [MainController::class, 'touristChangePassword']);
});
