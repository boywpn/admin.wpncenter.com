<?php

Route::group(['middleware' => ['web', 'permission:contactrequests.browse'], 'prefix' => 'contactrequests', 'as' => 'contactrequests.', 'namespace' => 'Modules\ContactRequests\Http\Controllers'], function () {
    Route::get('/', 'ContactRequestsController@indexRedirect');

    Route::group(['middleware' => ['web', 'permission:contactrequests.settings']], function () {

        Route::resource('contactrequeststatus', 'Settings\ContactRequestStatusController');
        Route::resource('preferredcontactmethod', 'Settings\PreferredContactMethodController');
        Route::resource('contactreason', 'Settings\ContactReasonController');


    });

    Route::resource('contactrequests', 'ContactRequestsController');
});
