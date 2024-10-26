<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::namespace('App\Http\Controllers\Api\V1')->prefix('v1')->name('v1')->group(function () {
    Route::post('sign-up','AuthContrlller@signup');
    Route::post('login','AuthContrlller@login');
    Route::post('file_upload','ProductController@file_upload');
    Route::post('get_prodcuts','ProductController@get_prodcuts');
});