<?php

Route::group(['middleware' => ['web','permission:settings.access'], 'prefix' => 'settings','as'=>'settings.', 'namespace' => 'Modules\Platform\Settings\Http\Controllers'], function () {

    Route::get('outgoing_server', ['as' => 'outgoing_server', 'uses' => 'OutgoingServerController@index']);
    Route::post('outgoing_server', ['as' => 'outgoing_server', 'uses' => 'OutgoingServerController@store']);
    Route::get('outgoing_server_refresh_cache', ['as' => 'outgoing_server_refresh_cache', 'uses' => 'OutgoingServerController@refreshSettingsCache']);
    Route::post('outgoing_server_test_email', ['as' => 'outgoing_server_test_email', 'uses' => 'OutgoingServerController@sendTestEmail']);

    Route::resource('language', 'LanguageController');

    Route::resource('currency', 'CurrencyController', []);

    Route::resource('tax', 'TaxController', []);

    Route::resource('dateformat', 'DateformatController', []);

    Route::resource('timeformat', 'TimeformatController', []);

    Route::resource('timezone', 'TimeZoneController', []);
});

Route::group(['middleware' => ['web','permission:company.settings'], 'prefix' => 'settings','as'=>'settings.', 'namespace' => 'Modules\Platform\Settings\Http\Controllers'], function () {

    Route::get('/', ['as' => 'index', 'uses' => 'SettingsController@index']);
    Route::get('clear-cache', ['as' => 'clear_cache', 'uses' => 'SettingsController@clearCache']);
    Route::get('optimize', ['as' => 'optimize', 'uses' => 'SettingsController@optimize']);
    Route::get('optimize_return', ['as' => 'optimize_return', 'uses' => 'SettingsController@optimizeReturn']);

    Route::get('company_settings', ['as' => 'company_settings', 'uses' => 'CompanySettingsController@index']);
    Route::post('company_settings', ['as' => 'company_settings', 'uses' => 'CompanySettingsController@store']);
    Route::get('display', ['as' => 'display', 'uses' => 'DisplayController@index']);
    Route::post('display', ['as' => 'display', 'uses' => 'DisplayController@store']);

});


