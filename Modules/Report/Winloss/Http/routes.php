<?php

Route::group(['prefix'=>'report', 'as'=>'report.',  'namespace' => 'Modules\Report\Winloss\Http\Controllers'], function()
{
    Route::group(['middleware' => ['web','permission:report.winloss.browse']], function()
    {
        Route::get('winloss', ['as'=>'winloss','uses'=> 'WinlossController@winloss']);
        Route::get('winloss/{game}', ['as'=>'winloss.game','uses'=> 'WinlossController@winloss']);
        Route::post('winloss/{game}', ['as'=>'winloss.game.search','uses'=> 'WinlossController@winloss']);

        Route::get('winloss-new', ['as'=>'winloss-new','uses'=> 'WinlossController@winlossNew']);
        Route::get('winloss-new/{game}', ['as'=>'winloss-new.game','uses'=> 'WinlossController@winlossNew']);
        Route::post('winloss-new/{game}', ['as'=>'winloss-new.game.search','uses'=> 'WinlossController@winlossNew']);
    });

    /**
     * Check Loss
    */
    Route::get('loss/{game}', ['as'=>'loss','uses'=> 'LossController@index']);
    // Route::get('pushloss/{game}/{act}', ['as'=>'loss','uses'=> 'LossController@pushBack']);
    Route::get('pushloss_auto/{game}/{act}', ['as'=>'loss','uses'=> 'LossController@pushBackAuto']);
});
