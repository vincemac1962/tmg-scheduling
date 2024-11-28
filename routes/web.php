<?php


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdvertiserController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SitesController;
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

// route to display sites for association to schedule
Route::get('/sites/selection/{schedule_id}', [SitesController::class, 'indexForSelection'])->name('sites.selection');
// Sites resource route
Route::resource('sites', App\Http\Controllers\SitesController::class);
// associate sites to a schedule
Route::post('/schedules/{schedule}/associate-sites', [ScheduleController::class, 'associateSites'])->name('schedules.associateSites');
// show associated sites for schedule
Route::get('/schedules/{schedule}/associated-sites', [ScheduleController::class, 'showAssociatedSites'])->name('schedules.associatedSites');
// remove associated site from schedule
Route::delete('/schedules/{schedule}/remove-site/{site}', [ScheduleController::class, 'removeAssociatedSite'])->name('schedules.removeSite');
// Schedules resource route
Route::resource('/schedules', App\Http\Controllers\ScheduleController::class);
// Schedule Items resource route
Route::resource('/schedule_items', App\Http\Controllers\ScheduleItemController::class);
// Schedule details report
Route::get('/schedules/{schedule}/details', [ScheduleController::class, 'viewDetails'])->name('schedules.viewDetails');

// get list of existing advertisers
Route::get('/advertisers/select', [AdvertiserController::class, 'selectExisting'])->name('advertisers.select');
// add selected advertisers to the schedule
Route::post('/schedule/addSelectedAdvertisers', [ScheduleController::class, 'addSelectedAdvertisers']);
// create and advertiser without a schedule id
Route::get('/advertisers/create-no-schedule', [AdvertiserController::class, 'createNoScheduleId'])->name('advertisers.createNoScheduleId');
// Advertiser resource route
Route::resource('/advertisers', App\Http\Controllers\AdvertiserController::class);


require __DIR__.'/auth.php';
