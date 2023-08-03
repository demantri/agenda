<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('login', [LoginController::class, 'index']);
// Route::get('/', [LoginController::class, 'index']);
Route::get('login', [LoginController::class, 'index']);
Route::post('doLogin', [LoginController::class, 'doLogin']);
Route::get('logout', [LoginController::class, 'logout']);

Route::get('dashboard', [DashboardController::class, 'index']);

Route::prefix('agenda')->group(function () {
    Route::get('/', [AgendaController::class, 'index']);
    Route::get('/google_calendar', [AgendaController::class, 'google_calendar']);
    Route::post('/save', [AgendaController::class, 'save']);
    Route::post('/', [AgendaController::class, 'filter']);
    Route::post('/detail', [AgendaController::class, 'detail']);
});


