<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\InvitationController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });

    Route::post('/register', [AuthController::class,'register']);
    Route::post('/login', [AuthController::class,'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class,'logout']);
        Route::get('/user', fn(Request $r) => $r->user());

        // rooms
        Route::get('/rooms', [RoomController::class,'index']);
        Route::post('/rooms', [RoomController::class,'store']);
        Route::get('/rooms/{room}', [RoomController::class,'show']);

        // messages
        Route::get('/rooms/{room}/messages', [MessageController::class,'index']);
        Route::post('/rooms/{room}/messages', [MessageController::class,'store']);

        // invitations
        Route::post('/rooms/{room}/invite', [RoomController::class,'invite']);
        Route::post('/invitations/{token}/accept', [InvitationController::class,'accept']);
    });
});
