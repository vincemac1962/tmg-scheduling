<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdvertiserController;
use App\Http\Controllers\ScheduleController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('phpinfo', function () {
    return view('phpinfo');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Sites resource route
Route::resource('sites', App\Http\Controllers\SitesController::class);
// Schedules resource route
Route::resource('schedules', App\Http\Controllers\ScheduleController::class);
// Schedule Items resource route
Route::resource('schedule_items', App\Http\Controllers\ScheduleItemController::class);
// get list of existing advertisers
Route::get('/advertisers/select', [AdvertiserController::class, 'selectExisting'])->name('advertisers.select');
Route::post('/schedule/addSelectedAdvertisers', [ScheduleController::class, 'addSelectedAdvertisers']);
// Advertiser resource route
Route::resource('advertisers', App\Http\Controllers\AdvertiserController::class);


require __DIR__.'/auth.php';
