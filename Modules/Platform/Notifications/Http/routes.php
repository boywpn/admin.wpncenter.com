<?php

Route::group(['middleware' => 'web', 'prefix' => 'notifications', 'namespace' => 'Modules\Platform\Notifications\Http\Controllers'], function () {

    Route::get('/', 'NotificationsController@index')->name('notifications.index');

    Route::get('/count-new', 'NotificationsController@countNewNotifications')->name('notifications.countNew');

    Route::post('/top-bar-notifications', 'NotificationsController@topBarNotifications')->name('notifications.topBar');

    Route::post('/mark-notification', 'NotificationsController@markAsRead')->name('notifications.mark');

    Route::post('/delete', 'NotificationsController@delete')->name('notifications.delete');

});
