<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartamentsController;
use App\Http\Controllers\LinesController;
use App\Http\Controllers\SafetyEventController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function() {
    Route::prefix('departaments')->group(function() {
        Route::get('/get_departaments', [DepartamentsController::class, 'fetchDepartamentsForDivision']);
    });
    Route::prefix('lines')->group(function() {
        Route::get('/get_lines', [LinesController::class, 'fetchLinesForDepartament']);
    });

    Route::prefix('events')->group(function() {
        Route::get('/events', [SafetyEventController::class, 'getEvents']);
        Route::post('/open_event', [SafetyEventController::class, 'openEvent']);
        Route::get('/event_reasons', [SafetyEventController::class, 'fetchReasonsForEvent']);
    });
});