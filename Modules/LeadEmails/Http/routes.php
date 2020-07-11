<?php

Route::group(['middleware' => ['web', 'permission:leads.browse'], 'prefix' => 'leademails', 'as' => 'leademails.', 'namespace' => 'Modules\LeadEmails\Http\Controllers'], function () {
    Route::get('/', 'LeadEmailsController@indexRedirect');

    Route::resource('leademails', 'LeadEmailsController');
});
