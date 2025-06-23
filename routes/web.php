<?php

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
Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'DashboardController@index')->name("index");
    Route::get('/dashboard', 'DashboardController@index')->name("dashboard");
    Route::get('/home', 'DashboardController@index')->name("home");

    Route::group(['prefix' => 'account'], function(){
        Route::group(['prefix' => 'farmer'], function(){
            Route::get('', 'FarmerController@index')->name("farmer.index")->middleware("can:administrator");
            Route::get('grid-list', 'FarmerController@datatables')->name("farmer.grid-list");
            Route::get('detail', 'FarmerController@detail')->name("farmer.detail");
            Route::post('submit', 'FarmerController@save')->name("farmer.submit")->middleware("can:administrator");
            Route::post('remove', 'FarmerController@delete')->name("farmer.remove")->middleware("can:administrator");
        });
        Route::group(['prefix' => 'vcp'], function(){
            Route::get('', 'VcpAccountController@index')->name("vcp-account.index")->middleware("can:administrator");
            Route::get('grid-list', 'VcpAccountController@datatables')->name("vcp-account.grid-list");
            Route::post('submit', 'VcpAccountController@save')->name("vcp-account.submit")->middleware("can:administrator");
            Route::post('remove', 'VcpAccountController@delete')->name("vcp-account.remove")->middleware("can:administrator");
        });
        Route::group(['prefix' => 'vch'], function(){
            Route::get('', 'VchAccountController@index')->name("vch-account.index")->middleware("can:administrator");
            Route::get('grid-list', 'VchAccountController@datatables')->name("vch-account.grid-list");
            Route::post('submit', 'VchAccountController@save')->name("vch-account.submit")->middleware("can:administrator");
            Route::post('remove', 'VchAccountController@delete')->name("vch-account.remove")->middleware("can:administrator");
        });
    });    
    Route::group(['prefix' => 'master-data'], function(){
        Route::group(['prefix' => 'location'], function(){
            Route::get('', 'LocationController@index')->name("location.index")->middleware("can:administrator");
            Route::get('grid-list', 'LocationController@datatables')->name("location.grid-list");
            Route::post('submit', 'LocationController@save')->name("location.submit")->middleware("can:administrator");
            Route::post('remove', 'LocationController@delete')->name("location.remove")->middleware("can:administrator");
        });
        Route::group(['prefix' => 'ho-approval'], function(){
            Route::get('', 'ApprovalController@index')->name("approval.index")->middleware("can:administrator");
            Route::get('grid-list', 'ApprovalController@datatables')->name("approval.grid-list");
            Route::post('submit', 'ApprovalController@save')->name("approval.submit")->middleware("can:administrator");
            Route::post('remove', 'ApprovalController@delete')->name("approval.remove")->middleware("can:administrator");
        });
        Route::group(['prefix' => 'user'], function(){
            Route::get('', 'UserController@index')->name("user.index")->middleware("can:administrator");
            Route::get('grid-list', 'UserController@datatables')->name("user.grid-list");
            Route::group(['prefix' => 'list'], function(){
                Route::get('combo-box', 'UserController@combo')->name("user.list.combobox");
            });
            Route::post('submit', 'UserController@save')->name("user.submit")->middleware("can:administrator");
            Route::post('remove', 'UserController@delete')->name("user.remove")->middleware("can:administrator");
        });
        Route::group(['prefix' => 'coverage'], function(){
            Route::get('province', 'LocationController@listComboProvince')->name("coverage.province");
            Route::get('city', 'LocationController@listComboCity')->name("coverage.city");
            Route::get('district', 'LocationController@listComboDistrict')->name("coverage.district");
            Route::get('sub-district', 'LocationController@listComboSubDistrict')->name("coverage.sub_district");
        });
        Route::group(['prefix' => 'evc'], function(){
            Route::get('', 'EvcController@index')->name("evc.index")->middleware("can:administrator");
            Route::get('grid-list', 'EvcController@datatables')->name("evc.grid-list");
            Route::post('submit', 'EvcController@save')->name("evc.submit")->middleware("can:administrator");
            Route::post('remove', 'EvcController@delete')->name("evc.remove")->middleware("can:administrator");
        });
        Route::group(['prefix' => 'vch'], function(){
            Route::get('', 'VCHController@index')->name("vch.index")->middleware("can:administrator");
            Route::get('grid-list', 'VCHController@datatables')->name("vch.grid-list");
            Route::post('submit', 'VCHController@save')->name("vch.submit")->middleware("can:administrator");
            Route::post('remove', 'VCHController@delete')->name("vch.remove")->middleware("can:administrator");
        });
        Route::group(['prefix' => 'vcp'], function(){
            Route::get('', 'VCPController@index')->name("vcp.index")->middleware("can:administrator");
            Route::get('grid-list', 'VCPController@datatables')->name("vcp.grid-list");
            Route::post('submit', 'VCPController@save')->name("vcp.submit")->middleware("can:administrator");
            Route::post('remove', 'VCPController@delete')->name("vcp.remove")->middleware("can:administrator");
        });
        Route::group(['prefix' => 'accounts'], function(){
            Route::get('', 'AccountController@index')->name("accounts.index")->middleware("can:administrator");
            Route::get('grid-list', 'AccountController@datatables')->name("accounts.grid-list");
            Route::post('submit', 'AccountController@save')->name("accounts.submit")->middleware("can:administrator");
            Route::post('remove', 'AccountController@delete')->name("accounts.remove")->middleware("can:administrator");
        });
    });

    Route::group(['prefix' => 'transaction'], function(){
        Route::group(['prefix' => 'purchase-order'], function(){
            Route::get('create', 'PurchaseOrderController@index')->name("purchase-order.create")->middleware("can:administrator");
            Route::get('grid-list', 'PurchaseOrderController@datatables')->name("purchase-order.grid-list");
            Route::post('submit', 'PurchaseOrderController@save')->name("purchase-order.submit");
            Route::post('update', 'PurchaseOrderController@update')->name("purchase-order.update");
            Route::post('remove', 'PurchaseOrderController@delete')->name("purchase-order.remove");
            Route::get('release', 'PurchaseOrderController@release')->name("purchase-order.release")->middleware("can:po-maker");
            Route::get('latest-history', 'PurchaseOrderController@latestHistory')->name("purchase-order.latest-history");
            Route::get('', 'TransactionController@index')->name("purchase-order.index")->middleware("can:administrator");
            Route::get('list', 'TransactionController@list')->name("purchase-order.transaction.grid-list")->middleware("can:administrator");
            Route::get('detail', 'TransactionController@detail')->name("purchase-order.transaction.detail")->middleware("can:administrator");
        });
    });
});
Route::group(['prefix' => 'email'], function(){
    Route::get('inbox', function () { return view('pages.email.inbox'); });
    Route::get('read', function () { return view('pages.email.read'); });
    Route::get('compose', function () { return view('pages.email.compose'); });
});

Route::group(['prefix' => 'apps'], function(){
    Route::get('chat', function () { return view('pages.apps.chat'); });
    Route::get('calendar', function () { return view('pages.apps.calendar'); });
});

Route::group(['prefix' => 'ui-components'], function(){
    Route::get('accordion', function () { return view('pages.ui-components.accordion'); });
    Route::get('alerts', function () { return view('pages.ui-components.alerts'); });
    Route::get('badges', function () { return view('pages.ui-components.badges'); });
    Route::get('breadcrumbs', function () { return view('pages.ui-components.breadcrumbs'); });
    Route::get('buttons', function () { return view('pages.ui-components.buttons'); });
    Route::get('button-group', function () { return view('pages.ui-components.button-group'); });
    Route::get('cards', function () { return view('pages.ui-components.cards'); });
    Route::get('carousel', function () { return view('pages.ui-components.carousel'); });
    Route::get('collapse', function () { return view('pages.ui-components.collapse'); });
    Route::get('dropdowns', function () { return view('pages.ui-components.dropdowns'); });
    Route::get('list-group', function () { return view('pages.ui-components.list-group'); });
    Route::get('media-object', function () { return view('pages.ui-components.media-object'); });
    Route::get('modal', function () { return view('pages.ui-components.modal'); });
    Route::get('navs', function () { return view('pages.ui-components.navs'); });
    Route::get('navbar', function () { return view('pages.ui-components.navbar'); });
    Route::get('pagination', function () { return view('pages.ui-components.pagination'); });
    Route::get('popovers', function () { return view('pages.ui-components.popovers'); });
    Route::get('progress', function () { return view('pages.ui-components.progress'); });
    Route::get('scrollbar', function () { return view('pages.ui-components.scrollbar'); });
    Route::get('scrollspy', function () { return view('pages.ui-components.scrollspy'); });
    Route::get('spinners', function () { return view('pages.ui-components.spinners'); });
    Route::get('tabs', function () { return view('pages.ui-components.tabs'); });
    Route::get('tooltips', function () { return view('pages.ui-components.tooltips'); });
});

Route::group(['prefix' => 'advanced-ui'], function(){
    Route::get('cropper', function () { return view('pages.advanced-ui.cropper'); });
    Route::get('owl-carousel', function () { return view('pages.advanced-ui.owl-carousel'); });
    Route::get('sortablejs', function () { return view('pages.advanced-ui.sortablejs'); });
    Route::get('sweet-alert', function () { return view('pages.advanced-ui.sweet-alert'); });
});

Route::group(['prefix' => 'forms'], function(){
    Route::get('basic-elements', function () { return view('pages.forms.basic-elements'); });
    Route::get('advanced-elements', function () { return view('pages.forms.advanced-elements'); });
    Route::get('editors', function () { return view('pages.forms.editors'); });
    Route::get('wizard', function () { return view('pages.forms.wizard'); });
});

Route::group(['prefix' => 'charts'], function(){
    Route::get('apex', function () { return view('pages.charts.apex'); });
    Route::get('chartjs', function () { return view('pages.charts.chartjs'); });
    Route::get('flot', function () { return view('pages.charts.flot'); });
    Route::get('peity', function () { return view('pages.charts.peity'); });
    Route::get('sparkline', function () { return view('pages.charts.sparkline'); });
});

Route::group(['prefix' => 'tables'], function(){
    Route::get('basic-tables', function () { return view('pages.tables.basic-tables'); });
    Route::get('data-table', function () { return view('pages.tables.data-table'); });
});

Route::group(['prefix' => 'icons'], function(){
    Route::get('feather-icons', function () { return view('pages.icons.feather-icons'); });
    Route::get('mdi-icons', function () { return view('pages.icons.mdi-icons'); });
});

Route::group(['prefix' => 'general'], function(){
    Route::get('blank-page', function () { return view('pages.general.blank-page'); });
    Route::get('faq', function () { return view('pages.general.faq'); });
    Route::get('invoice', function () { return view('pages.general.invoice'); });
    Route::get('profile', function () { return view('pages.general.profile'); });
    Route::get('pricing', function () { return view('pages.general.pricing'); });
    Route::get('timeline', function () { return view('pages.general.timeline'); });
});

Route::group(['prefix' => 'auth'], function(){
    Route::get('login', function () { return view('pages.auth.login'); });
    Route::get('register', function () { return view('pages.auth.register'); });
});

Route::group(['prefix' => 'error'], function(){
    Route::get('404', function () { return view('pages.error.404'); });
    Route::get('500', function () { return view('pages.error.500'); });
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// 404 for undefined routes
Route::any('/{page?}',function(){
    return View::make('pages.error.404');
})->where('page','.*');

require __DIR__.'/auth.php';
