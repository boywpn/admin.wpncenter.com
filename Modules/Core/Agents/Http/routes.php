<?php

Route::group(['middleware' => ['web','permission:core.agents.browse'], 'prefix'=>'core', 'as'=>'core.', 'namespace' => 'Modules\Core\Agents\Http\Controllers'], function () {
    Route::get('/', 'AgentsController@index');

    Route::group(['middleware' => ['web','permission:core.agents.settings'], 'prefix'=>'agents', 'as'=>'agents.'], function () {
        Route::resource('status', 'Settings\AgentsStatusController');
    });

    Route::resource('agents', 'AgentsController');

    Route::get('agents/copy-old/{partner}', 'AgentsController@copyOld');

    Route::post('agents/share_config/{entityId}', ['as'=>'agents.share_config.save','uses'=> 'AgentsController@saveShareConfig']);
    Route::get('agents/token/{entityId}', ['as'=>'agents.token','uses'=> 'AgentsController@agentToken']);
});
