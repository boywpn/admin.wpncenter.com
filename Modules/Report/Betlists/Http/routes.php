<?php

Route::group(['middleware' => 'web', 'prefix' => 'report/betlists', 'namespace' => 'Modules\Report\Betlists\Http\Controllers'], function()
{
    Route::get('/', 'BetlistsController@index');
});
