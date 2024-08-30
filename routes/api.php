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

Route::post('login', 'MobileController@login')->middleware('authapi:mobile');

Route::group(['prefix' => 'master-data'], function(){
    Route::get('coverage', 'LocationController@coverage')->middleware('authapi:mobile');
    Route::get('farmer', 'FarmerController@list')->middleware('authapi:mobile');
    Route::group(['prefix' => 'purchase-order'], function(){
        Route::get('list', 'PurchaseOrderController@list')->middleware('authapi:mobile');
    });
});