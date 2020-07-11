<?php

Route::group(['middleware' => ['web', 'permission:contacts.browse'], 'prefix' => 'contactemails', 'as' => 'contactemails.', 'namespace' => 'Modules\ContactEmails\Http\Controllers'], function () {
    Route::get('/', 'ContactEmailsController@indexRedirect');

    Route::resource('contactemails', 'ContactEmailsController');
});
