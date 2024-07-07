<?php

use App\Http\Controllers\api\attraction_activityController;
use App\Http\Controllers\api\charge_walletController;
use App\Http\Controllers\api\favoriteController;
use App\Http\Controllers\api\hotelController;
use App\Http\Controllers\api\locationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\api\rateAndCommentController;
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
    Route::get('/touristGetHotels', [hotelController::class, 'touristGetHotels']);
    Route::get('/touristGetHotelById/{id}', [hotelController::class, 'touristGetHotelById']);
    Route::get('/touristGetResturants', [resturantController::class, 'touristGetResturants']);
    Route::get('/touristGetResturantById/{id}', [resturantController::class, 'touristGetResturantById']);
    Route::get('/touristGetAttraction_activites', [attraction_activityController::class, 'touristGetAttraction_activites']);
    Route::get('/touristGetAttraction_activiteById/{id}', [attraction_activityController::class, 'touristGetAttraction_activiteById']);
    Route::get('/touristGetTrips', [tripController::class, 'touristGetTrips']);
    Route::get('/touristGetTripsReserved', [tripController::class, 'touristGetTripsReserved']);
    Route::get('/touristGetTripById/{id}', [tripController::class, 'touristGetTripById']);
    Route::get('/touristGetAllFavorite', [favoriteController::class, 'touristGetAllFavorite']);
    Route::get('/touristGetAllRateAndComment', [rateAndCommentController::class, 'touristGetAllRateAndComment']);

    Route::post('/touristChangePassword', [MainController::class, 'touristChangePassword']);
    Route::post('/touristUpdateProfile', [MainController::class, 'touristUpdateProfile']);
    Route::post('/touristPutDeleteFavorite', [favoriteController::class, 'touristPutDeleteFavorite']);
    Route::post('/touristChargeCode', [charge_walletController::class, 'touristChargeCode']);
    Route::post('/touristPutComment', [rateAndCommentController::class, 'touristPutComment']);
    Route::post('/touristPutRate', [rateAndCommentController::class, 'touristPutRate']);
    Route::post('/touristReserveTrip', [tripController::class, 'touristReserveTrip']);



    Route::put('/touristUpdateReserveTrip', [tripController::class, 'touristUpdateReserveTrip']);



    Route::delete('/touristDeleteEmail', [MainController::class, 'touristDeleteEmail']);
    Route::delete('/touristDeleteReserveTrip/{id}', [tripController::class, 'touristDeleteReserveTrip']);



    Route::get('/Admin/adminLogout', [MainController::class, 'logout']);
    Route::get('/Admin/adminGetServices', [hotelController::class, 'adminGetServices']);
    Route::get('/Admin/adminGetHotels', [hotelController::class, 'adminGetHotels']);
    Route::get('/Admin/adminGetHotelById/{id}', [hotelController::class, 'adminGetHotelById']);
    Route::get('/Admin/adminGetResturants', [resturantController::class, 'adminGetResturants']);
    Route::get('/Admin/adminGetResturantById/{id}', [resturantController::class, 'adminGetResturantById']);
    Route::get('/Admin/adminGetAttraction_activites', [attraction_activityController::class, 'adminGetAttraction_activites']);
    Route::get('/Admin/adminGetAttraction_activiteById/{id}', [attraction_activityController::class, 'adminGetAttraction_activiteById']);
    Route::get('/Admin/adminGetAllCities', [locationsController::class, 'adminGetAllCities']);
    Route::get('/Admin/adminGetCitiesByNation/{nation_id}', [locationsController::class, 'adminGetCitiesByNation']);
    Route::get('/Admin/adminGetAllNations', [locationsController::class, 'adminGetAllNations']);
    Route::get('/Admin/adminGetAllCharges', [charge_walletController::class, 'adminGetAllCharges']);
    Route::get('/Admin/adminGetTrips', [tripController::class, 'adminGetTrips']);
    Route::get('/Admin/adminGetTripById/{id}', [tripController::class, 'adminGetTripById']);

    Route::post('/Admin/adminCreateHotel', [hotelController::class, 'adminCreateHotel']);
    Route::post('/Admin/adminCreateRooms', [hotelController::class, 'adminCreateRooms']);
    Route::post('/Admin/adminCreateResturant', [resturantController::class, 'adminCreateResturant']);
    Route::post('/Admin/adminCreateAttraction_activity', [attraction_activityController::class, 'adminCreateAttraction_activity']);
    Route::post('/Admin/adminCreateTrip', [tripController::class, 'adminCreateTrip']);
    Route::post('/Admin/adminCreateCity', [locationsController::class, 'adminCreateCity']);
    Route::post('/Admin/adminCreateNation', [locationsController::class, 'adminCreateNation']);
    Route::post('/Admin/adminCharge', [charge_walletController::class, 'adminCharge']);


    Route::put('/Admin/adminUpdateCity', [locationsController::class, 'adminUpdateCity']);
    Route::put('/Admin/adminUpdateNation', [locationsController::class, 'adminUpdateNation']);
    Route::put('/Admin/adminUpdateHotel', [hotelController::class, 'adminUpdateHotel']);
    Route::put('/Admin/adminUpdateRoom', [hotelController::class, 'adminUpdateRoom']);
    Route::put('/Admin/adminUpdateResturant', [resturantController::class, 'adminUpdateResturant']);
    Route::put('/Admin/adminUpdateAttraction_activity', [attraction_activityController::class, 'adminUpdateAttraction_activity']);
    Route::put('/Admin/adminUpdateTrip', [tripController::class, 'adminUpdateTrip']);

    Route::delete('/Admin/adminDeleteNation/{id}', [locationsController::class, 'adminDeleteNation']);
    Route::delete('/Admin/adminDeleteCity/{id}', [locationsController::class, 'adminDeleteCity']);
    Route::delete('/Admin/adminDeleteHotel/{id}', [hotelController::class, 'adminDeleteHotel']);
    Route::delete('/Admin/adminDeleteRoom/{room_id}', [hotelController::class, 'adminDeleteRoom']);
    Route::delete('/Admin/adminDeleteResturant/{id}', [resturantController::class, 'adminDeleteResturant']);
    Route::delete('/Admin/adminDeleteAttraction_activity/{id}', [attraction_activityController::class, 'adminDeleteAttraction_activity']);
    Route::delete('/Admin/adminDeleteTrip/{id}', [tripController::class, 'adminDeleteTrip']);
});

// Route::group(["middleware" => ["auth:sanctum"], "middleware" => ["check.admin"]], function () {
//     // Route::post('/Admin/adminCreateHotel', [hotelController::class, 'adminCreateHotel']);
// });
