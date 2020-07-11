<?php

Route::group(['middleware' => ['web','permission:settings.access'], 'prefix' => 'settings','as'=>'settings.', 'namespace' => 'Modules\Platform\User\Http\Controllers'], function () {

    Route::resource('roles', 'Roles\RolesController', []);

    Route::get('/roles/permissions/{id}', ['as' => 'roles.permissions', 'uses' => 'Roles\PermissionsController@setup']);
    Route::post('/roles/permissions/{id}', ['as' => 'roles.permissions-save', 'uses' => 'Roles\PermissionsController@save']);

    Route::get('sharing_rules', ['as' => 'sharing_rules', 'uses' => 'SharingRulesController@index']);
});

Route::group(['middleware' => ['web','permission:company.settings'], 'prefix' => 'settings','as'=>'settings.', 'namespace' => 'Modules\Platform\User\Http\Controllers'], function () {
    Route::resource('users', 'User\UserController', []);

    Route::get('/users/ghost-login/{identifier}', ['as' => 'users.login-as', 'uses' => 'User\UserGhostLoginController@login']);
    Route::post('/users/change-password/{identifier}', ['as' => 'users.change-password', 'uses' => 'User\UserChangePasswordController@changePassword']);
    Route::get('/users/activity/{identifier}', ['as' => 'users.activity', 'uses' => 'User\UserActivityController@activity']);
    Route::get('/users/token/{identifier}', ['as' => 'users.token', 'uses' => 'User\UserController@genToken']);

    Route::resource('groups', 'GroupsController', []);
});

