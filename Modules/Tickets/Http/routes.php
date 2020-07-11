<?php

Route::group(['middleware' => ['web', 'permission:tickets.browse'], 'prefix' => 'tickets', 'as' => 'tickets.', 'namespace' => 'Modules\Tickets\Http\Controllers'], function () {
    Route::get('/', 'TicketsController@indexRedirect');

    Route::group(['middleware' => ['web', 'permission:tickets.settings']], function () {
        Route::resource('priority', 'Settings\PriorityController');
        Route::resource('status', 'Settings\StatusController');
        Route::resource('severity', 'Settings\SeverityController');
        Route::resource('category', 'Settings\CategoryController');
    });

    Route::resource('tickets', 'TicketsController');

    Route::get('tickets/tickets-selection/{entityId}', ['as'=>'tickets.selection','uses'=> 'Tabs\TicketsTicketsController@selection']);
    Route::get('tickets/tickets-linked/{entityId}', ['as'=>'tickets.linked','uses'=> 'Tabs\TicketsTicketsController@linked']);
    Route::post('tickets/tickets-unlink', ['as'=>'tickets.unlink','uses'=> 'Tabs\TicketsTicketsController@unlink']);
    Route::post('tickets/tickets-link', ['as'=>'tickets.link','uses'=> 'Tabs\TicketsTicketsController@link']);

});
