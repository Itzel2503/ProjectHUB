<?php

use App\Http\Controllers\Customer;
use App\Http\Controllers\Permit;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Projects\Project;
use App\Http\Controllers\Report;
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
    Route::resource('/profile', ProfileController::class)->only(['index']);

    Route::resource('/userCatalog', UserCatalog::class)->only(['index']);

    Route::resource('/customers', Customer::class);

    Route::resource('/projects', Project::class);

    Route::get('{id_project}/reports', [Report::class, 'index'])->name('reports.index');
    Route::get('{id_project}/reports/create', [Report::class, 'create'])->name('reports.create');
    Route::get('reports', [Report::class, 'store'])->name('reports.store');

    Route::resource('/permits', Permit::class);
});

// Rutas de autenticación generadas por Auth::routes();
Auth::routes();