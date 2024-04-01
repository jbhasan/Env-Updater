<?php

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

Route::get('/env-updater', [\Sayeed\EnvUpdater\Http\Controllers\EnvUpdaterController::class, 'envPermission']);
Route::post('/env-updater', [\Sayeed\EnvUpdater\Http\Controllers\EnvUpdaterController::class, 'envPermissionUpdate']);
Route::get('/env-updater/logout', [\Sayeed\EnvUpdater\Http\Controllers\EnvUpdaterController::class, 'envPermissionLogout']);
Route::get('/env-updater/edit', [\Sayeed\EnvUpdater\Http\Controllers\EnvUpdaterController::class, 'showEnv']);
Route::post('/env-updater/update', [\Sayeed\EnvUpdater\Http\Controllers\EnvUpdaterController::class, 'updateEnv']);
