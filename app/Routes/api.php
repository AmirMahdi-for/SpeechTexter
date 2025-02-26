<?php

use App\Services\SpeechTexter\Controllers\SpeeecTexterController;
use Illuminate\Support\Facades\Route;
use SpeechTexter\Controllers\SpeechTexterController;

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

Route::prefix('SpeechTexter')->group(function () {
    
    Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
        Route::get('list',  [SpeechTexterController::class, 'list']);
    });

});
