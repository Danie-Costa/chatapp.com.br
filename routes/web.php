<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\RoomWebController;

Route::get('/', [AuthWebController::class, 'showLogin'])->name('showLogin');
Route::post('/login', [AuthWebController::class, 'login']);
Route::get('/register', [AuthWebController::class, 'showRegister']);
Route::post('/register', [AuthWebController::class, 'register']);
Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

Route::middleware('session.token')->group(function () {
    Route::get('/rooms', [RoomWebController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{id}', [RoomWebController::class, 'show'])->name('rooms.show');
});
