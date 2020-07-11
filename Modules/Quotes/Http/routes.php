<?php

Route::group(['middleware' => ['web', 'permission:quotes.browse'], 'prefix' => 'quotes', 'as' => 'quotes.', 'namespace' => 'Modules\Quotes\Http\Controllers'], function () {
    Route::get('/', 'QuotesController@indexRedirect');

    Route::group(['middleware' => ['web', 'permission:quotes.settings']], function () {
        Route::resource('stage', 'Settings\StageController');
        Route::resource('carrier', 'Settings\CarrierController');
    });

    Route::get('quotes/print/{id}', 'QuotesController@printQuote')->name('quotes.print');


    Route::resource('quotes', 'QuotesController');

    Route::get('quotes/convert_to_order/{id}', ['as'=>'quotes.convert.to.order','uses'=> 'QuotesController@convertToOrder']);

    Route::post('company-settings', 'QuotesController@companySettings');

    Route::post('copy-account', 'QuotesController@copyDataFromAccount');

    Route::post('load-product', 'QuotesController@loadProduct');
});
