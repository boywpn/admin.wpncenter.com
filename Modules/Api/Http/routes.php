<?php

/**
 * More information about rate limit
 * https://github.com/GrahamCampbell/Laravel-Throttle
 */
Route::group(['middleware' => ['web','json.request','GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware:50,30'], 'prefix' => 'api/webform', 'namespace' => 'Modules\Api\Http\Controllers'], function () {

    Route::group(['prefix' => 'contacts'], function (){
        Route::post('create','Webform\ContactsWebformController@store');
    });

    Route::group(['prefix' => 'leads'], function (){
        Route::post('create','Webform\LeadsWebformController@store');
    });

});


Route::group(['middleware' => 'web', 'prefix' => 'api', 'namespace' => 'Modules\Api\Http\Controllers'], function () {

    Route::any('login', 'ApiAuthController@login');

    Route::group(['prefix' => 'saas'], function (){
        Route::post('/register','Saas\SaasApiController@registerCompany');

        Route::post('/deactivate-account','Saas\SaasApiController@deactivateAccount');

        Route::post('/resume-account','Saas\SaasApiController@resumeAccount');

        Route::post('/update-plan','Saas\SaasApiController@updatePlan');

    });

    Route::group(['prefix' => 'leads'], function (){
        Route::get('/','LeadsApiController@index');
        Route::get('get/{id}','LeadsApiController@get');
        Route::post('create','LeadsApiController@store');
        Route::post('update/{id}','LeadsApiController@update');
        Route::delete('delete/{id}','LeadsApiController@destroy');
    });

    Route::group(['prefix' => 'contacts'], function (){
        Route::get('/','ContactsApiController@index');
        Route::get('get/{id}','ContactsApiController@get');
        Route::post('create','ContactsApiController@store');
        Route::post('update/{id}','ContactsApiController@update');
        Route::delete('delete/{id}','ContactsApiController@destroy');
    });

    Route::group(['prefix' => 'tickets'], function (){
        Route::get('/','TicketsApiController@index');
        Route::get('get/{id}','TicketsApiController@get');
        Route::post('create','TicketsApiController@store');
        Route::post('update/{id}','TicketsApiController@update');
        Route::delete('delete/{id}','TicketsApiController@destroy');
    });

    Route::group(['prefix' => 'members'], function (){
        Route::get('/','Member\MembersApiController@index');
        Route::get('get/{id}','Member\MembersApiController@get');
        Route::post('create','Member\MembersApiController@store');
        Route::post('update/{id}','Member\MembersApiController@update');
        Route::delete('delete/{id}','Member\MembersApiController@destroy');

        Route::get('add-member/{id}','Member\MembersApiController@addMember');
        Route::get('gen-username/{id}/{order}','Member\MembersApiController@genUsername');
        Route::post('login','Member\MembersApiController@memberLogin');
        Route::post('loginfun','Member\MembersApiController@memberLoginFun');
        Route::post('login-username','Member\MembersApiController@memberLoginUsername');
    });

    Route::group(['prefix' => 'jobs'], function (){
        Route::get('/','Job\JobsApiController@index');
        Route::get('get/{id}','Job\JobsApiController@get');
        Route::post('create','Job\JobsApiController@genJob');
    });

    /**
     * Game Api
    */
    Route::group(['prefix' => 'game'], function (){
        Route::post('transfer','Game\TransferApiController@setTransfer');
        Route::post('sa/betlimit/{boardID}', ['as'=>'sa.betlimit','uses'=> 'StatementsController@push']);
    });

    /**
     * UPC Api
     */
    Route::group(['prefix' => 'upc', 'namespace' => 'Upc'], function (){

        Route::any('/', ['uses'=> 'UpcController@index']);

        Route::group(['prefix' => 'v1'], function (){

            Route::any('/', ['uses'=> 'UpcController@index']);

            Route::get('/check_auth', ['uses'=> 'UpcController@checkAuth']);

            Route::group(['namespace' => 'V1'], function (){

                Route::group(['prefix' => 'member'], function (){

                    Route::post('login', ['uses'=> 'MembersController@login']);
                    Route::post('first_update', ['uses'=> 'MembersController@firstUpdate']);
                    Route::post('confirm_auto', ['uses'=> 'MembersController@confirmAuto']);

                    Route::post('username', ['uses'=> 'MembersController@username']);

                });

                Route::group(['prefix' => 'wallet'], function (){

                    Route::post('balance', ['uses'=> 'WalletController@balance']);
                    Route::post('transfer', ['uses'=> 'WalletController@transfer']);
                    Route::post('confirm', ['uses'=> 'WalletController@confirm']);
                    Route::post('deposit', ['uses'=> 'WalletController@deposit']);
                    Route::post('histories', ['uses'=> 'WalletController@histories']);

                });

                Route::group(['prefix' => 'bank'], function (){

                    Route::post('statement', ['uses'=> 'BanksController@statement']);

                });

            });

        });
    });

    /**
     * Admin Api
     */
    Route::group(['middleware' => 'admin', 'prefix' => 'admin', 'namespace' => 'Admin'], function (){

        Route::group(['prefix' => 'v1', 'namespace' => 'V1'], function (){

            /**
             * Core
             */
            Route::group(['prefix' => 'core', 'namespace' => 'Core'], function (){

                Route::post('partner', ['as'=>'partner','uses'=> 'PartnersController@action']);
                Route::post('agent', ['as'=>'agent','uses'=> 'AgentsController@action']);
                Route::post('member', ['as'=>'member','uses'=> 'MembersController@action']);
                Route::post('username', ['as'=>'username','uses'=> 'UsernamesController@action']);

            });

        });

        Route::any('/', ['uses'=> 'AdminController@index']);
        Route::any('/v1', ['uses'=> 'AdminController@index']);
        Route::any('/v1/core', ['uses'=> 'AdminController@index']);

    });

});
