<?php

use App\Http\Controllers\Permit;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserCatalog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

// Grupo de rutas protegidas por autenticación
Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('/permits', Permit::class);
    Route::resource('/profile', ProfileController::class)->only(['index']);
    Route::resource('/userCatalog', UserCatalog::class)->only(['index']);
});

// Rutas de autenticación generadas por Auth::routes();
Auth::routes();