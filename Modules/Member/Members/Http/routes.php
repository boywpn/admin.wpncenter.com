<?php

Route::group(['middleware' => ['web','permission:member.members.browse'], 'prefix'=>'member', 'as'=>'member.', 'namespace' => 'Modules\Member\Members\Http\Controllers'], function () {
    Route::get('/', 'MembersController@index');

    Route::get('members/gen_auto', 'MembersController@genAuto');
    Route::get('members/gen_username_member', 'MembersController@genUsernameMember');

    Route::group(['middleware' => ['web','permission:member.members.settings'], 'prefix'=>'members', 'as'=>'members.'], function () {
        Route::resource('status', 'Settings\MembersStatusController');
    });

    Route::group(['middleware' => ['web','permission:member.members.banks.browse'], 'prefix'=>'members', 'as'=>'members.'], function () {
        Route::resource('banks', 'MembersBanksController');

        Route::get('members/banks-selection/{entityId}', ['as'=>'banks.selection','uses'=> 'Tabs\MembersBanksControllerTab@selection']);
        Route::get('members/banks-linked/{entityId}', ['as'=>'banks.linked','uses'=> 'Tabs\MembersBanksControllerTab@linked']);
        Route::post('members/banks-unlink', ['as'=>'banks.unlink','uses'=> 'Tabs\MembersBanksControllerTab@unlink']);
        Route::post('members/banks-link', ['as'=>'banks.link','uses'=> 'Tabs\MembersBanksControllerTab@link']);
    });

    Route::resource('members', 'MembersController');
    Route::post('members/commission/{entityId}', ['as'=>'members.commission.save','uses'=> 'MembersController@saveCommission']);
    Route::post('members/games_config/{entityId}', ['as'=>'members.games_config.save','uses'=> 'MembersController@saveGamesConfig']);

    Route::get('members/add_member/{id}', 'MembersController@addMember');
    Route::get('members/gen_username/{entityId}', ['as'=>'members.gen_username','uses'=> 'MembersController@genUsername']);
    Route::get('members/set_bet_limit/{entityId}', ['as'=>'members.set_bet_limit','uses'=> 'MembersController@setBetLimit']);

    Route::get('members/username-selection/{entityId}', ['as'=>'members.username.selection','uses'=> 'Tabs\MembersUsernameControllerTab@selection']);
    Route::get('members/username-linked/{entityId}', ['as'=>'members.username.linked','uses'=> 'Tabs\MembersUsernameControllerTab@linked']);
    Route::post('members/username-unlink', ['as'=>'members.username.unlink','uses'=> 'Tabs\MembersUsernameControllerTab@unlink']);
    Route::post('members/username-link', ['as'=>'members.username.link','uses'=> 'Tabs\MembersUsernameControllerTab@link']);
});
