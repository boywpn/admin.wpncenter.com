<?php

Route::group(['middleware' => 'web', 'prefix' => 'account', 'namespace' => 'Modules\Platform\Account\Http\Controllers'], function () {

    Route::get('/', ['as'=>'account.index','uses'=> 'AccountController@preferences']);

    Route::get('ghost-logout', ['as'=>'account.ghost-logout','uses'=> 'GhostLogoutController@logout']);

    Route::get('activity-log', ['as'=>'account.activity-log','uses'=> 'AccountController@activityLog']);

    Route::post('ajax_update_account_settings', ['as'=>'account.ajax_update_account_settings','uses'=> 'AccountController@ajaxUpdateAccountSettings']);

    Route::post('update', ['as'=>'account.update','uses'=>'AccountController@updateAccount']);

    Route::post('change-password', ['as'=>'account.password','uses'=>'AccountController@changePassword']);

});
