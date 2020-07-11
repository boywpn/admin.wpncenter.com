<?php

Route::group(['middleware' => 'web', 'prefix' => 'wallet/jobs', 'namespace' => 'Modules\Wallet\Jobs\Http\Controllers'], function()
{
    Route::get('/', 'JobsController@index');
});
