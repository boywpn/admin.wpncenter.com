<?php

Route::group(['middleware' => ['web'], 'prefix' => 'calendar', 'as' => 'calendar.', 'namespace' => 'Modules\Calendar\Http\Controllers'], function () {
    Route::get('/calendar-events/{calendarId}', ['as' => 'events', 'uses' => 'CalendarViewController@ajaxEvents']);

    Route::get('accessible-calendars', ['as'=>'accessible-calendars','uses'=>'CalendarViewController@ajaxAccessibleCalendars']);

    Route::post('/event-manage', ['as' => 'event.drop','uses'=>'CalendarViewController@manageEvent']);

    Route::get('/', ['as' => 'index', 'uses' => 'CalendarViewController@index']);

    Route::group(['middleware' => ['web', 'permission:calendar.settings']], function () {
        Route::resource('priority', 'Settings\EventPriorityController');
        Route::resource('status', 'Settings\EventStatusController');
    });

    Route::resource('events', 'EventsController');
    Route::resource('calendars', 'CalendarsController');
});
