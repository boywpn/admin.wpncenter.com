<?php

Route::group(['middleware' => ['web','permission:core.boards.browse'], 'prefix'=>'core', 'as'=>'core.', 'namespace' => 'Modules\Core\Boards\Http\Controllers'], function () {
    Route::get('/', 'BoardsController@index');

    Route::group(['middleware' => ['web','permission:core.boards.users.browse'], 'prefix'=>'boards', 'as'=>'boards.'], function () {
        Route::resource('users', 'BoardsUsersController');
    });

    Route::resource('boards', 'BoardsController');

    Route::get('boards/create_members/{entityId}', ['as'=>'boards.create_members','uses'=> 'BoardsController@createMembers']);
    Route::get('boards/change_pass_username/{entityId}', ['as'=>'boards.change_pass_username','uses'=> 'BoardsController@changePassUsername']);
    Route::get('boards/transfer_credit/{entityId}', ['as'=>'boards.transfer_credit','uses'=> 'BoardsController@transferCredit']);

    Route::get('boards/users-selection/{entityId}', ['as'=>'boards.users.selection','uses'=> 'Tabs\BoardsUsersControllerTab@selection']);
    Route::get('boards/users-linked/{entityId}', ['as'=>'boards.users.linked','uses'=> 'Tabs\BoardsUsersControllerTab@linked']);
    Route::post('boards/users-unlink', ['as'=>'boards.users.unlink','uses'=> 'Tabs\BoardsUsersControllerTab@unlink']);
    Route::post('boards/users-link', ['as'=>'boards.users.link','uses'=> 'Tabs\BoardsUsersControllerTab@link']);

    Route::get('boards/members-selection/{entityId}', ['as'=>'boards.members.selection','uses'=> 'Tabs\BoardsMembersControllerTab@selection']);
    Route::get('boards/members-linked/{entityId}', ['as'=>'boards.members.linked','uses'=> 'Tabs\BoardsMembersControllerTab@linked']);
    Route::post('boards/members-unlink', ['as'=>'boards.members.unlink','uses'=> 'Tabs\BoardsMembersControllerTab@unlink']);
    Route::post('boards/members-link', ['as'=>'boards.members.link','uses'=> 'Tabs\BoardsMembersControllerTab@link']);
});
