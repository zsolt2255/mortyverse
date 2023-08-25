<?php

use App\Http\Controllers\API\CharacterController;
use App\Http\Controllers\API\EpisodeController;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'episodes','as' => 'episodes.'], function () {
    Route::get('sync', [EpisodeController::class, 'sync'])->name('sync');
    Route::get('{episode}/characters', [EpisodeController::class, 'characters'])->name('characters');
});

Route::apiResource('episodes', EpisodeController::class)->only([
    'index',
]);
Route::apiResource('characters', CharacterController::class)->only([
    'show'
]);
