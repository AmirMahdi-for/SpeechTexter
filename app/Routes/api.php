<?php

use App\Services\SpeeechTexter\Controllers\SpeeechTexterController;
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

Route::prefix('SpeeechTexter')->group(function () {
    
    Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
        Route::get('list',  [SpeeechTexterController::class, 'list']);
    });

});
