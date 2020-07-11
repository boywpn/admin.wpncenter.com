<?php

Route::group(['middleware' => ['web','permission:core.username.browse'], 'prefix'=>'core', 'as'=>'core.', 'namespace' => 'Modules\Core\Username\Http\Controllers'], function () {

    Route::get('username/gen_username_auto', 'UsernameController@genUsernameAuto');

    Route::get('username/create_more', ['as' => 'username.create_more', 'uses' => 'UsernameController@create_more']);

    Route::get('username/push_username/{entityId}', ['as'=>'username.push_username','uses'=> 'UsernameController@pushUsername']);
    Route::get('username/push_username_id/{entityId}', ['as'=>'username.push_username_id','uses'=> 'UsernameController@pushUsernameAction']);
    Route::get('username/BalanceByBoard/{entityId}', ['as'=>'username.balance_by_agent','uses'=> 'UsernameController@balanceByBoard']);
    Route::get('username/bet_limit_board/{entityId}', ['as'=>'username.bet_limit_board','uses'=> 'UsernameController@betLimitByBoard']);
    Route::post('username/bet_limit/{entityId}', ['as'=>'username.bet_limit','uses'=> 'UsernameController@setBetLimit']);
    Route::get('username/create_by_board/{entityId}', ['as'=>'username.create_by_board','uses'=> 'UsernameController@createByBoard']);

    Route::get('username/topup_event/{entityId}', ['as'=>'username.topup_event','uses'=> 'UsernameController@topupEvent']);

    Route::get('username/set_userev_by_board/{entityId}', ['as'=>'username.set_userev_by_board','uses'=> 'UsernameController@setUserEvByBoard']);
    Route::get('username/dis_userev_by_board/{entityId}/{status}', ['as'=>'username.dis_userev_by_board','uses'=> 'UsernameController@disableUserEvByBoard']);
    Route::get('username/dis_userev_by_id/{entityId}/{status}', ['as'=>'username.dis_userev_by_board','uses'=> 'UsernameController@disableUserEvByID']);
    Route::get('username/events', ['as' => 'username.events', 'uses' => 'UsernameController@doEvents']);
    Route::post('username/events-give', ['as' => 'username.events-give', 'uses' => 'UsernameController@confirmGive']);

    Route::resource('username', 'UsernameController');
});
