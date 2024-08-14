<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiScheduleController;
use App\Http\Controllers\ApiScheduleItemController;

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