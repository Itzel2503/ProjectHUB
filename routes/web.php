<?php

use App\Http\Controllers\ActivitiesReports\ActivityReport;
use App\Http\Controllers\Projects\Activity;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Customers\Customer;
use App\Http\Controllers\EffortPoints\EffortPoints;
use App\Http\Controllers\Inventory\Inventory;
use App\Http\Controllers\Notion\Notion;
use App\Http\Controllers\Projects\Priority;
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
    Route::get('profile', function () {
        return view('auth/profile');
    })->name('profile.index');
    // USERS
    Route::get('userCatalog', function () {
        return view('users/user-catalog');
    })->name('userCatalog.index')->middleware('user.type:1');
    // PROJECTS
    Route::resource('projects', Project::class)->only(['index']);
    Route::resource('priority', Priority::class)->middleware('user.type:1,2');
    Route::resource('projects.reports', Report::class);
    Route::resource('projects.activities', Activity::class)->only(['index'])->middleware('user.type:1,2');
    // ACTIVITIES
    Route::resource('/activities-reports', ActivityReport::class)->only(['index'])->middleware('user.type:1,2');
    // NOTION
    Route::resource('/notion', Notion::class);
    // EFFORT POINTS
    Route::get('effortPoints', function () {
        return view('effortpoints/effortpoints');
    })->name('effortPoints.index')->middleware('user.type:1');
    // INVENTORY
    Route::get('storage', function () {
        return view('inventory/inventory');
    })->name('storage.index')->middleware('user.type:1', 'user.area:1');
});
// Rutas de autenticación generadas por Auth::routes();
Auth::routes();