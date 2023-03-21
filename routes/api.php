<?php

use Illuminate\Support\Facades\Broadcast;
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

Route::prefix('/v1')->group(function() {
    Route::group(['middleware' => ['cors', 'json.response']], static function () {

        // public routes
//        Route::post('/login', 'App\Http\Controllers\API\AuthController@login')->name('auth.login');
        Route::post('/register','App\Http\Controllers\API\AuthController@register')->name('auth.register');
        Route::post('/validate','App\Http\Controllers\API\AuthController@validateCode')->name('auth.validate-code');

        Route::middleware('auth:sanctum')->group(function () {
            // protected routes
            Route::post('/logout', 'App\Http\Controllers\API\AuthController@logout')->name('auth.logout');
            Route::get('/user', 'App\Http\Controllers\API\UserController@getCurrentUser')->name('user.current');
            Route::put('/user', 'App\Http\Controllers\API\UserController@updateUser')->name('user.current-update');
            Route::delete('/user', 'App\Http\Controllers\API\UserController@deleteUser')->name('user.current-delete');
            Route::post('/user/change', 'App\Http\Controllers\API\AuthController@changeCredentials')->name('user.current-change');
            Route::post('/user/change/validate', 'App\Http\Controllers\API\AuthController@validateNewCredentials')->name('user.change-validate');
        });
    });
});

Broadcast::routes(['middleware' => ['auth:sanctum']]);
