<?php

Route::group(['middleware' => ['web','permission:assets.browse'],'prefix'=>'assets','as'=>'assets.', 'namespace' => 'Modules\Assets\Http\Controllers'], function () {
    Route::get('/','AssetsController@indexRedirect');

    Route::group(['middleware' => ['web','permission:assets.settings']], function () {
        Route::resource('status', 'Settings\StatusController');
        Route::resource('category', 'Settings\CategoryController');
        Route::resource('manufacturer', 'Settings\ManufacturerController');
    });

    Route::resource('assets', 'AssetsController');
});
