<?php

Route::group(['middleware' => 'upc', 'prefix' => 'upc', 'namespace' => 'Modules\Upc\Http\Controllers'], function()
{
    Route::get('/', 'UpcController@index');

    Route::group(['prefix' => 'v1'], function (){

        Route::any('/', ['uses'=> 'UpcController@index']);

//        Route::get('/check_auth', ['uses'=> 'UpcController@checkAuth']);

        Route::group(['namespace' => 'V1'], function (){

            Route::group(['prefix' => 'member'], function (){

                Route::post('login', ['uses'=> 'MembersController@login']);
                Route::post('register', ['uses'=> 'MembersController@register']);
                Route::post('check_exist', ['uses'=> 'MembersController@checkExist']);
                Route::post('first_update', ['uses'=> 'MembersController@firstUpdate']);
                Route::post('confirm_auto', ['uses'=> 'MembersController@confirmAuto']);

                Route::post('username', ['uses'=> 'MembersController@username']);
                Route::post('generate-username', ['uses'=> 'MembersController@GenerateUsername']);
                Route::post('info', ['uses'=> 'MembersController@info']);

                Route::post('deposit', ['uses'=> 'MembersController@deposit']);
                Route::post('withdraw', ['uses'=> 'MembersController@withdraw']);
                Route::post('transfer-log', ['uses'=> 'MembersController@transferLog']);

            });

            Route::group(['prefix' => 'username'], function (){

                Route::post('balance', ['uses'=> 'UsernamesController@getTransfer']);
                Route::post('game-login', ['uses'=> 'UsernamesController@gameLogin']);

            });

            Route::group(['prefix' => 'wallet'], function (){

                Route::post('balance', ['uses'=> 'WalletController@balance']);
                Route::post('transfer', ['uses'=> 'WalletController@transfer']);
                Route::post('confirm', ['uses'=> 'WalletController@confirm']);
                Route::post('deposit', ['uses'=> 'WalletController@deposit']);
                Route::post('withdraw', ['uses'=> 'WalletController@withdraw']);
                Route::post('histories', ['uses'=> 'WalletController@histories']);

            });

            Route::group(['prefix' => 'bank'], function (){

                Route::post('statement', ['uses'=> 'BanksController@statement']);

            });

        });

    });
});
