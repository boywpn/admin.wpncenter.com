<?php

Route::group(['middleware' => 'web', 'prefix' => 'core/codes', 'namespace' => 'Modules\Core\Codes\Http\Controllers'], function()
{
    Route::get('/', 'CodesController@index');
});
