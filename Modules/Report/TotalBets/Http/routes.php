<?php

Route::group(['middleware' => 'web', 'prefix' => 'report/totalbets', 'namespace' => 'Modules\Report\TotalBets\Http\Controllers'], function()
{
    Route::get('/', 'TotalBetsController@index');

    Route::get('/ufa', 'TotalBetsController@ufa');
});
