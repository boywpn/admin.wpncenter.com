<?php

Route::group(['middleware' => ['web', 'permission:orders.browse'], 'prefix' => 'orders', 'as' => 'orders.', 'namespace' => 'Modules\Orders\Http\Controllers'], function () {
    Route::get('/', 'OrdersController@indexRedirect');

    Route::group(['middleware' => ['web', 'permission:orders.settings']], function () {
        Route::resource('status', 'Settings\StatusController');
        Route::resource('carrier', 'Settings\CarrierController');
    });

    Route::get('orders/print/{id}', 'OrdersController@printOrder')->name('orders.print');

    Route::resource('orders', 'OrdersController');

    Route::get('orders/convert_to_invoie/{id}', ['as'=>'orders.convert.to.invoice','uses'=> 'OrdersController@convertToInvoice']);


    Route::post('company-settings', 'OrdersController@companySettings');

    Route::post('copy-account', 'OrdersController@copyDataFromAccount');

    Route::post('load-product', 'OrdersController@loadProduct');
});
