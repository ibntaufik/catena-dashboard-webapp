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

Route::post('login', 'MobileController@login')->middleware('authapi:auth');

Route::group(['prefix' => 'master-data'], function(){
    Route::post('coverage', 'LocationController@coverage')->middleware('authapi:mobile');
    Route::group(['prefix' => 'farmer'], function(){
        Route::post('', 'FarmerController@list')->middleware('authapi:mobile');
        Route::post('register', 'FarmerController@register')->middleware('authapi:mobile');
        Route::post('update', 'FarmerController@update')->middleware('authapi:mobile');
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
    Route::group(['prefix' => 'list'], function(){
        Route::post('', 'TransactionController@list')->middleware('authapi:mobile');
        Route::post('user', 'TransactionController@listByUser')->middleware('authapi:mobile');
    });    
    
    Route::post('purchase-order', 'TransactionController@submit')->middleware('authapi:mobile');

    Route::group(['prefix' => 'farmer'], function(){
        Route::group(['prefix' => 'read-asset'], function(){
            Route::post('public', 'FarmerController@readAssetPublic')->middleware('authapi:mobile');
            Route::post('private', 'FarmerController@readAssetPrivate')->middleware('authapi:mobile');
        });
    });

    Route::group(['prefix' => 'pulper'], function(){
        Route::group(['prefix' => 'read-asset'], function(){
            Route::post('public', 'VCPController@readAssetPublic')->middleware('authapi:mobile');
            Route::post('private', 'VCPController@readAssetPrivate')->middleware('authapi:mobile');
        });
    });

    Route::group(['prefix' => 'huller'], function(){
        Route::group(['prefix' => 'read-asset'], function(){
            Route::post('public', 'VCHController@readAssetPublic')->middleware('authapi:mobile');
            Route::post('private', 'VCHController@readAssetPrivate')->middleware('authapi:mobile');
        });
    });

    Route::group(['prefix' => 'export'], function(){
        Route::group(['prefix' => 'read-asset'], function(){
            Route::post('public', 'EvcController@readAssetPublic')->middleware('authapi:mobile');
            Route::post('private', 'EvcController@readAssetPrivate')->middleware('authapi:mobile');
        });
    });

    Route::group(['prefix' => 'head-office'], function(){
        Route::group(['prefix' => 'read-asset'], function(){
            Route::post('public', 'HeadOfficeController@readAssetPublic')->middleware('authapi:mobile');
            Route::post('private', 'HeadOfficeController@readAssetPrivate')->middleware('authapi:mobile');
        });
    });
});