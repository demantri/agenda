<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
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
    Route::post('/update', [AgendaController::class, 'update']);
    Route::post('/', [AgendaController::class, 'filter']);
    Route::post('/detail', [AgendaController::class, 'detail']);
    Route::post('/hapus', [AgendaController::class, 'hapus']);
});

Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('store', [UserController::class, 'store']);
    Route::post('update', [UserController::class, 'update']);
    Route::get('delete/{id}', [UserController::class, 'delete']);
});

Route::get('/', function () {
    return redirect('agenda');
});


