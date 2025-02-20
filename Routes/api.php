<?php

use App\Services\Ussistant\Controllers\UssistantController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('ussistant')->group(function () {
    
    Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
        Route::get('list',  [UssistantController::class, 'list']);
    });

});
