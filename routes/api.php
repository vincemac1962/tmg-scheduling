<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiScheduleController;
use App\Http\Controllers\ApiScheduleItemController;
use App\Http\Controllers\ApiSiteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::middleware('log.route')->group(function () {
    // Route to get schedules
    Route::get('/schedules/{siteId}', [ApiScheduleController::class, 'getSchedules']);

    // Route to get files
    Route::get('/get-file', [ApiScheduleController::class, 'getFile']);

    // Route to log upload
    Route::post('/log-upload', [ApiScheduleItemController::class, 'logItemUpload']);

    // Route to log schedule download
    Route::post('/log-schedule', [ApiScheduleController::class, 'logScheduleUpload']);

    // route to get site id
    Route::get('/get-site-id', [ApiScheduleController::class, 'getSiteId']);

    // Route to register site for content
    Route::post('/register-content', [ApiSiteController::class, 'registerSite']);

    // Route to log site update
    Route::post('/log-site-update', [ApiSiteController::class, 'logSiteUpdate']);

    // Route to update last contact status of site
    Route::post('/update-last-contact', [ApiSiteController::class, 'updateSiteLastContact']);


});


