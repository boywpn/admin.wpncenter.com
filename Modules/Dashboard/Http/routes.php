<?php

Route::group(['middleware' => 'web', 'prefix' => 'dashboard', 'namespace' => 'Modules\Dashboard\Http\Controllers'], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::get('/new-tickets', 'DashboardController@getNewTickets')->name('dashboard.new-tickets');
});
