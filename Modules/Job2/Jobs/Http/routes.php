<?php

Route::group(['middleware' => 'web', 'prefix' => 'job2', 'namespace' => 'Modules\Jobs2\Jobs\Http\Controllers'], function () {

    // Route::get('jobs/deposit', ['as'=>'jobs.deposit', 'uses' => 'DepositController@index']);
    Route::get('/', function(){return "TEST";});

});
