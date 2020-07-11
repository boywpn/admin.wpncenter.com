<?php

Route::group(['middleware' => ['web','permission:owners.browse'], 'prefix' => 'core', 'as' => 'core.', 'namespace' => 'Modules\Core\Owners\Http\Controllers'], function()
{
    Route::resource('owners', 'OwnersController');

    Route::get('/owners/token/{identifier}', ['as' => 'owners.token', 'uses' => 'OwnersController@genToken']);
});