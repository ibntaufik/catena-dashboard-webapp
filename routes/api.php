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
    Route::post('coverage', 'LocationController@coverage')->middleware('authapi:mobile');
    Route::group(['prefix' => 'farmer'], function(){
        Route::post('', 'FarmerController@list')->middleware('authapi:mobile');
        Route::post('register', 'FarmerController@register')->middleware('authapi:mobile');
    });
    Route::post('vcp', 'VCPController@list')->middleware('authapi:mobile');
    Route::group(['prefix' => 'item'], function(){
        Route::post('', 'PurchaseOrderController@item')->middleware('authapi:mobile');
        Route::post('type', 'PurchaseOrderController@itemType')->middleware('authapi:mobile');
    });
    Route::group(['prefix' => 'purchase-order'], function(){
        Route::post('list', 'PurchaseOrderController@list')->middleware('authapi:mobile');
    });
});

Route::group(['prefix' => 'transaction'], function(){
    Route::post('list', 'TransactionController@list')->middleware('authapi:mobile');
    Route::post('purchase-order', 'TransactionController@submit')->middleware('authapi:mobile');
});