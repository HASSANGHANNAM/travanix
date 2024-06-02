<?php

use App\Http\Controllers\api\attraction_activityController;
use App\Http\Controllers\api\favoriteController;
use App\Http\Controllers\api\hotelController;
use App\Http\Controllers\api\locationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\api\resturantController;
use App\Http\Controllers\api\tripController;

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
    Route::get('/touristProfile', [MainController::class, 'touristProfile']);
    Route::get('/touristGetAllCities', [locationsController::class, 'touristGetAllCities']);
    Route::get('/touristGetCitiesByNation/{nation_id}', [locationsController::class, 'touristGetCitiesByNation']);
    Route::get('/touristGetAllNations', [locationsController::class, 'touristGetAllNations']);


    Route::post('/touristChangePassword', [MainController::class, 'touristChangePassword']);
    Route::post('/touristUpdateProfile', [MainController::class, 'touristUpdateProfile']);
    Route::post('/touristPutDeleteFavorite', [favoriteController::class, 'touristPutDeleteFavorite']);



    Route::get('/Admin/adminLogout', [MainController::class, 'logout']);
    Route::get('/Admin/adminGetHotels', [hotelController::class, 'adminGetHotels']);
    Route::get('/Admin/adminGetResturants', [resturantController::class, 'adminGetResturants']);
    Route::get('/Admin/adminGetAttraction_activites', [attraction_activityController::class, 'adminGetAttraction_activites']);
    Route::get('/Admin/adminGetHotelById/{id}', [hotelController::class, 'adminGetHotelById']);
    Route::get('/Admin/adminGetResturantById/{id}', [resturantController::class, 'adminGetResturantById']);
    Route::get('/Admin/adminGetAttraction_activiteById/{id}', [attraction_activityController::class, 'adminGetAttraction_activiteById']);
    Route::get('/Admin/adminGetAllCities', [locationsController::class, 'adminGetAllCities']);
    Route::get('/Admin/adminGetCitiesByNation/{nation_id}', [locationsController::class, 'adminGetCitiesByNation']);
    Route::get('/Admin/adminGetAllNations', [locationsController::class, 'adminGetAllNations']);


    Route::post('/Admin/adminCreateHotel', [hotelController::class, 'adminCreateHotel']);
    Route::post('/Admin/adminCreateRooms', [hotelController::class, 'adminCreateRooms']);
    Route::post('/Admin/adminCreateResturant', [resturantController::class, 'adminCreateResturant']);
    Route::post('/Admin/adminCreateAttraction_activity', [attraction_activityController::class, 'adminCreateAttraction_activity']);
    Route::post('/Admin/adminCreateTrip', [tripController::class, 'adminCreateTrip']);
    Route::post('/Admin/adminCreateCity', [locationsController::class, 'adminCreateCity']);
    Route::post('/Admin/adminCreateNation', [locationsController::class, 'adminCreateNation']);


    Route::put('/Admin/adminUpdateCity', [locationsController::class, 'adminUpdateCity']);
    Route::put('/Admin/adminUpdateNation', [locationsController::class, 'adminUpdateNation']);
    Route::put('/Admin/adminUpdateHotel', [hotelController::class, 'adminUpdateHotel']);
    Route::put('/Admin/adminUpdateRooms', [hotelController::class, 'adminUpdateRooms']);
    Route::put('/Admin/adminUpdateResturant', [resturantController::class, 'adminUpdateResturant']);
    Route::put('/Admin/adminUpdateAttraction_activity', [attraction_activityController::class, 'adminUpdateAttraction_activity']);
    Route::put('/Admin/adminUpdateTrip', [tripController::class, 'adminUpdateTrip']);

    Route::delete('/Admin/adminDeleteNation/{id}', [locationsController::class, 'adminDeleteNation']);
    Route::delete('/Admin/adminDeleteCity/{id}', [locationsController::class, 'adminDeleteCity']);
    Route::delete('/Admin/adminDeleteHotel/{id}', [hotelController::class, 'adminDeleteHotel']);
    Route::delete('/Admin/adminDeleteRoom/{hotel_id}/{room_id}', [hotelController::class, 'adminDeleteRoom']);
    Route::delete('/Admin/adminDeleteResturant/{id}', [resturantController::class, 'adminDeleteResturant']);
    Route::delete('/Admin/adminDeleteAttraction_activity/{id}', [attraction_activityController::class, 'adminDeleteAttraction_activity']);
    Route::delete('/Admin/adminDeleteTrip/{id}', [tripController::class, 'adminDeleteTrip']);
});
