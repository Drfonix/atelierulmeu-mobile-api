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
            Route::get('/refresh-token', 'App\Http\Controllers\API\AuthController@getRefreshToken')->name('user.refresh-token');

            Route::get('/information', 'App\Http\Controllers\API\GeneralController@getInformation')->name('general.information');

            Route::get('/cars/{car}', 'App\Http\Controllers\API\CarController@getCarById')->name('car.car-by-id');
            Route::get('/cars', 'App\Http\Controllers\API\CarController@getUserCars')->name('car.user-cars');
            Route::post('/cars', 'App\Http\Controllers\API\CarController@createUserCar')->name('car.create');
            Route::put('/cars/{car}', 'App\Http\Controllers\API\CarController@updateUserCar')->name('car.update');
            Route::delete('/cars/{car}', 'App\Http\Controllers\API\CarController@deleteUserCar')->name('car.delete');

            Route::get('/appointments/{appointment}', 'App\Http\Controllers\API\AppointmentRequestController@getById')->name('appointment.by-id');
            Route::get('/appointments', 'App\Http\Controllers\API\AppointmentRequestController@getUserAppointments')->name('appointment.user-appointments');
            Route::post('/appointments', 'App\Http\Controllers\API\AppointmentRequestController@createUserAppointment')->name('appointment.create');
            Route::put('/appointments/{appointment}', 'App\Http\Controllers\API\AppointmentRequestController@updateUserAppointment')->name('appointment.update');
            Route::delete('/appointments/{appointment}', 'App\Http\Controllers\API\AppointmentRequestController@deleteUserAppointment')->name('appointment.delete');

            Route::get('/alerts/{alert}', 'App\Http\Controllers\API\AlertController@getAlertById')->name('alert.by-id');
            Route::get('/alerts', 'App\Http\Controllers\API\AlertController@getUserAlerts')->name('alert.user-alerts');
            Route::post('/alerts', 'App\Http\Controllers\API\AlertController@createUserAlert')->name('alert.create');
            Route::put('/alerts/{alert}', 'App\Http\Controllers\API\AlertController@updateUserAlert')->name('alert.update');
            Route::delete('/alerts/{alert}', 'App\Http\Controllers\API\AlertController@deleteUserAlert')->name('alert.delete');

            Route::post('/images', 'App\Http\Controllers\API\UserImageController@postUploadUserImage');
            Route::get('/images', 'App\Http\Controllers\API\UserImageController@getUserImages');
            Route::post('/images/{userImage}', 'App\Http\Controllers\API\UserImageController@postEditImage');
            Route::delete('/images/{userImage}', 'App\Http\Controllers\API\UserImageController@deleteImage');
            Route::get('/images/{userImage}', 'App\Http\Controllers\API\UserImageController@getViewImage');

            Route::post('/documents', 'App\Http\Controllers\API\UserDocumentController@postUploadUserDocument');
            Route::get('/documents', 'App\Http\Controllers\API\UserDocumentController@getUserDocuments');
            Route::post('/documents/{userDocument}', 'App\Http\Controllers\API\UserDocumentController@postEditDocument');
            Route::delete('/documents/{userDocument}', 'App\Http\Controllers\API\UserDocumentController@deleteDocument');
            Route::get('/documents/{userDocument}', 'App\Http\Controllers\API\UserDocumentController@getViewDocument');
        });
    });
});

Broadcast::routes(['middleware' => ['auth:sanctum']]);
