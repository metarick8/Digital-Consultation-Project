<?php

use App\Http\Controllers\ExpertsController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UsersController;
use App\Models\Expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes:
// For Expert:
// Authentication:
Route::post('/expert/login', [ExpertsController::class, 'login']);
Route::post('/expert/register', [ExpertsController::class, 'register']);

// View all experts
Route::get('/expert/getAllExperts', [ExpertsController::class, 'getAllExperts']);

// Get reservations
Route::post('/expert/getReservations', [ReservationController::class, 'getReservations']);

// View The Types:
Route::post('/expert/getType', [ExpertsController::class, 'getType']);

// For User:
// Authentication:
Route::post('/user/login', [UsersController::class, 'login']);
Route::post('/user/register', [UsersController::class, 'register']);

// Protected routes:
Route::group(['middleware' => ['auth:sanctum']], function()
{
    // Authentication:
    Route::post('/expert/logout', [ExpertsController::class, 'logout']);
    Route::post('/user/logout', [UsersController::class, 'logout']);

    // Expert
    // Store The Types:
    Route::post('/expert/storeTypes', [ExpertsController::class, 'storeTypes']);
    // Rate The Expert
    Route::post('expert/rate', [ExpertsController::class, 'rate']);

    // Store Reservation
    Route::post('expert/storeReservations', [ReservationController::class, 'storeReservations']);
    // Update Books
    Route::post('expert/isBooked', [ReservationController::class, 'isBooked']);

    // User:
     // Store  Favorite:
     Route::post('user/storeFavorite', [UsersController::class, 'storeFavorite']);
     // Delete  Favorite:
     Route::post('user/deleteFavorite', [UsersController::class, 'deleteFavorite']);
     // Get The Favorites:
     Route::get('user/getAllFavorites', [UsersController::class, 'getAllFavorites']);
});




