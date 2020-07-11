<?php

Route::group(['middleware' => ['web', 'permission:deals.browse'], 'prefix' => 'deals', 'as' => 'deals.', 'namespace' => 'Modules\Deals\Http\Controllers'], function () {
    Route::get('/','DealsController@indexRedirect');

    Route::group(['middleware' => ['web', 'permission:deals.settings']], function () {
        Route::resource('stage', 'Settings\StageController');
        Route::resource('businesstype', 'Settings\BusinessTypeController');
    });

    Route::resource('deals', 'DealsController');

    Route::get('deals/contacts-selection/{entityId}', ['as'=>'contacts.selection','uses'=> 'Tabs\DealsContactsController@selection']);
    Route::get('deals/contacts-linked/{entityId}', ['as'=>'contacts.linked','uses'=> 'Tabs\DealsContactsController@linked']);
    Route::post('deals/contacts-unlink', ['as'=>'contacts.unlink','uses'=> 'Tabs\DealsContactsController@unlink']);
    Route::post('deals/contacts-link', ['as'=>'contacts.link','uses'=> 'Tabs\DealsContactsController@link']);
});
