<?php

Route::group(['middleware' => 'web', 'prefix' => 'core/settings', 'namespace' => 'Modules\Core\Settings\Http\Controllers'], function()
{
    Route::get('/', 'SettingsController@index');
});
