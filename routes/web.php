<?php

use App\Http\Controllers\ActivitiesReports\ActivityReport;
use App\Http\Controllers\Projects\Activity;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Customers\Customer;
use App\Http\Controllers\EffortPoints\EffortPoints;
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
    if (auth()->check()) {
        if (auth()->user()->type_user == 3) {
            return redirect('/projects');
        } else {
            return redirect('/activities-reports');
        }
    }
    return view('auth.login');
});
// Grupo de rutas protegidas por autenticación
Route::middleware(['web', 'auth'])->group(function () {
    // PROFILE
    Route::resource('profile', ProfileController::class)->only(['index']);
    // USERS
    Route::resource('userCatalog', UserCatalog::class)->only(['index'])->middleware('user.type:1');
    // CUSTOMERS
    Route::resource('customers', Customer::class)->middleware('user.type:1');
    // PROJECTS
    Route::resource('projects', Project::class)->only(['index']);
    Route::resource('projects.reports', Report::class);
    Route::resource('projects.activities', Activity::class)->only(['index'])->middleware('user.type:1,2');
    // ACTIVITIES
    Route::resource('/activities-reports', ActivityReport::class)->only(['index'])->middleware('user.type:1,2');
    // EFFORT POINTS
    Route::resource('/effortPoints', EffortPoints::class)->only(['index'])->middleware('user.type:1');
});
// Rutas de autenticación generadas por Auth::routes();
Auth::routes();