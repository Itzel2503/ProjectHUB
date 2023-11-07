<?php

use App\Http\Controllers\PerfilController;
use App\Http\Controllers\user\Perfil;
use App\Http\Controllers\user\PerfilController as UserPerfilController;
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

Auth::routes();

Route::get('/home', [PerfilController::class, 'index'])->name('home');
Route::resource('/perfil', UserPerfilController::class);

