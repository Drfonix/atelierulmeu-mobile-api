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
Route::get('download', static function () {
    return redirect()->away('https://drive.google.com/drive/folders/1331nP-7lSKyZ11YW6jwx391oSPU6xkxv?usp=sharing');
});
