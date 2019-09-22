<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//lights
Route::get('lights', 'LightsController@index');
Route::get('light/{id}', 'LightsController@show');
Route::post('light', 'LightsController@store');
Route::put('light', 'LightsController@store');
Route::delete('light/{id}', 'LightsController@destroy');