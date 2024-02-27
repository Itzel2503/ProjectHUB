<?php

use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Customers\Customer;
use App\Http\Controllers\Permit;
use App\Http\Controllers\Projects\Project;
use App\Http\Controllers\Projects\Report;
use App\Http\Controllers\Users\UserCatalog;
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

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/profile');
    }
    return view('auth.login');
});

// Grupo de rutas protegidas por autenticación
Route::middleware(['web', 'auth'])->group(function () {
    // USER
    Route::resource('/profile', ProfileController::class)->only(['index']);

    // USERS
    Route::resource('/userCatalog', UserCatalog::class)->only(['index']);

    // CUSTOMERS
    Route::resource('/customers', Customer::class);

    // PROJECTS
    Route::resource('/projects', Project::class);
    Route::resource('projects.reports', Report::class);
    
    // PERMITS
    Route::resource('/permits', Permit::class);
});

// Rutas de autenticación generadas por Auth::routes();
Auth::routes();