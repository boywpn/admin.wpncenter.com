<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'web', 'prefix' => 'games', 'as' => 'games.', 'namespace' => 'Games'], function()
{


    Route::post('events/login', ['as'=>'events.login','uses'=> 'EventsController@login']);

    /**
     * SBO Gaming
     */
    Route::group(['middleware' => 'web', 'prefix' => 'sbo', 'as' => 'sbo.', 'namespace' => 'Sbo'], function()
    {
        Route::resource('/', 'SboController');

        // Member
        Route::get('member', 'MemberController@index');
        Route::post('member/create', ['as'=>'member.create','uses'=> 'MemberController@create']);
        Route::post('member/login', ['as'=>'member.login','uses'=> 'MemberController@login']);
        Route::post('member/logout', ['as'=>'member.logout','uses'=> 'MemberController@logout']);
        Route::post('member/balance', ['as'=>'member.balance','uses'=> 'MemberController@balance']);
        Route::post('member/get-bet-limit', ['as'=>'member.get-bet-limit','uses'=> 'MemberController@getBetlimit']);
        Route::post('member/set-bet-limit', ['as'=>'member.get-bet-limit','uses'=> 'MemberController@setBetlimit']);
        Route::post('member/his-comm', ['as'=>'member.his-comm','uses'=> 'MemberController@getHistoryCommission']);
//        Route::post('member/login', ['as'=>'member.login','uses'=> 'MemberController@login']);
//        Route::post('member/demo', ['as'=>'member.demo','uses'=> 'MemberController@demo']);
//        Route::post('member/balance', ['as'=>'member.balance','uses'=> 'MemberController@balance']);
//        Route::post('member/balanceupdate', ['as'=>'member.balanceupdate','uses'=> 'MemberController@balanceupdate']);
    });

    /**
     * DG Gaming
    */
    Route::group(['middleware' => 'web', 'prefix' => 'dg', 'as' => 'dg.', 'namespace' => 'Dg'], function()
    {
        Route::resource('/', 'DGController');

        // Member
        Route::get('member', 'MemberController@index');
        Route::post('member/create', ['as'=>'member.create','uses'=> 'MemberController@create']);
        Route::post('member/login', ['as'=>'member.login','uses'=> 'MemberController@login']);
        Route::post('member/demo', ['as'=>'member.demo','uses'=> 'MemberController@demo']);
        Route::post('member/balance', ['as'=>'member.balance','uses'=> 'MemberController@balance']);
        Route::post('member/balanceupdate', ['as'=>'member.balanceupdate','uses'=> 'MemberController@balanceupdate']);
        Route::get('lock_board/{board}', ['as'=>'member.lock_board','uses'=> 'MemberController@lockByBoard']);
    });

    /**
     * OG Gaming
     */
//    Route::group(['middleware' => 'web', 'prefix' => 'og', 'as' => 'og.', 'namespace' => 'Og'], function()
//    {
//        Route::resource('/', 'OGController');
//
//        // Member
//        Route::get('member', 'MemberController@index');
//        Route::post('member/create', ['as'=>'member.create','uses'=> 'MemberController@create']);
//        Route::post('member/player', ['as'=>'member.player','uses'=> 'MemberController@player']);
//        Route::post('member/login', ['as'=>'member.login','uses'=> 'MemberController@login']);
//        Route::post('member/demo', ['as'=>'member.demo','uses'=> 'MemberController@demo']);
//        Route::post('member/balance', ['as'=>'member.balance','uses'=> 'MemberController@balance']);
//        Route::post('member/balanceupdate', ['as'=>'member.balanceupdate','uses'=> 'MemberController@balanceupdate']);
//        Route::post('member/transaction', ['as'=>'member.transaction','uses'=> 'MemberController@transaction']);
//    });

    /**
     * SA Gaming
     */
    Route::group(['middleware' => 'web', 'prefix' => 'sa', 'as' => 'sa.', 'namespace' => 'Sa'], function()
    {
        Route::resource('/', 'SAController');

        // Member
        Route::get('member/login/{username}/{table}', ['as'=>'member.login','uses'=> 'MemberController@login']);
//        Route::get('member', 'MemberController@index');
//        Route::get('bet_limit/{board}', 'MemberController@betLimit');
//        Route::post('member/create', ['as'=>'member.create','uses'=> 'MemberController@create']);
//        Route::post('member/status', ['as'=>'member.status','uses'=> 'MemberController@status']);
//        Route::post('member/login', ['as'=>'member.login','uses'=> 'MemberController@login']);
//        Route::post('member/demo', ['as'=>'member.demo','uses'=> 'MemberController@demo']);
//        Route::post('member/balance', ['as'=>'member.balance','uses'=> 'MemberController@balance']);
//        Route::post('member/balanceupdate', ['as'=>'member.balanceupdate','uses'=> 'MemberController@balanceupdate']);
//        Route::post('member/transaction', ['as'=>'member.transaction','uses'=> 'MemberController@transaction']);
    });

    /**
     * Sexy Casino
     */
    Route::group(['middleware' => 'web', 'prefix' => 'sexy', 'as' => 'sexy.', 'namespace' => 'Sexy'], function()
    {
        Route::resource('/', 'SexyController');

        // Member
        Route::get('member/login/{username}', ['as'=>'member.login','uses'=> 'MemberController@login']);
//        Route::get('member', 'MemberController@index');
//        Route::get('bet_limit/{board}', 'MemberController@betLimit');
//        Route::post('member/create', ['as'=>'member.create','uses'=> 'MemberController@create']);
//        Route::post('member/status', ['as'=>'member.status','uses'=> 'MemberController@status']);
//        Route::post('member/login', ['as'=>'member.login','uses'=> 'MemberController@login']);
//        Route::post('member/demo', ['as'=>'member.demo','uses'=> 'MemberController@demo']);
//        Route::post('member/balance', ['as'=>'member.balance','uses'=> 'MemberController@balance']);
//        Route::post('member/balanceupdate', ['as'=>'member.balanceupdate','uses'=> 'MemberController@balanceupdate']);
//        Route::post('member/transaction', ['as'=>'member.transaction','uses'=> 'MemberController@transaction']);
    });

    // Route::get('report/bet-item/{gameCode}', 'ReportController@getBetItem');
    Route::get('report/balance/{board}/{username}', 'ReportController@getBalanceByBoard');
    Route::get('report/balance/{board}/{username}', 'ReportController@getBalanceByBoard');
    Route::match(['get', 'post'],'report/bet-item/{gameCode}', 'ReportController@getBetItemByUser');
    Route::match(['get', 'post'],'report/bet-items/{gameCode}', 'ReportController@getBetItem');

    Route::get('report/fixtures/{type_id}/{bet_type}', 'ReportController@getFixtures');

    Route::get('report/bet-limit-list/{boardID}', ['as'=>'report.bet-limit-list','uses'=> 'ReportController@listBetLimit']);

    /**
     * For SA
     */
    Route::post('seamless/callback/sa/{action}', ['as'=>'seamless.callback.sa','uses'=> 'SeamlessController@SACallback']);

});

/**
 * Route for auto transaction
*/
Route::group(['middleware' => 'web', 'prefix' => 'autodp', 'as' => 'autodp.', 'namespace' => 'AutoDeposit'], function()
{

    Route::get('/', ['as'=>'index','uses'=> 'AutoDepositController@index']);
    // Route::get('/{id}', ['as'=>'index-test','uses'=> 'AutoDepositController@test']);
    Route::get('/for_admin', ['as'=>'index','uses'=> 'AutoDepositAdminController@index']); // For admin auto only
    Route::get('/for_admin_test/{id}', ['as'=>'index','uses'=> 'AutoDepositAdminController@indexTest']); // For admin auto only
    Route::get('/test_pusher', ['as'=>'test_pusher','uses'=> 'AutoDepositAdminController@test_pusher']); // For admin auto only
    Route::get('/monitor', ['as'=>'monitor','uses'=> 'MonitorController@monitor']); // For admin auto only
    Route::get('/match_name', ['as'=>'match_name','uses'=> 'MonitorController@matchName']); // For admin auto only
    Route::post('/from_statement', ['as'=>'from_statement','uses'=> 'AutoDepositController@createByStatement']);

});

/**
 * AppWallet
*/
Route::group(['middleware' => 'web', 'prefix' => 'wallet', 'as' => 'wallet.', 'namespace' => 'Wallet'], function()
{
    Route::post('/callback', ['as'=>'callback','uses'=> 'AppWalletController@callback']);
});

/**
 * Sms
 */
Route::group(['middleware' => 'web', 'prefix' => 'sms', 'as' => 'sms.', 'namespace' => 'Sms'], function()
{
    Route::get('/receive', ['as'=>'receive','uses'=> 'SmsController@receive']);
    Route::get('/test', ['as'=>'test','uses'=> 'SmsController@test']);
});

/**
 * Lotto
 */
Route::group(['middleware' => 'web', 'prefix' => 'lotto', 'as' => 'lotto.', 'namespace' => 'Lotto'], function()
{
    Route::get('/get_result/{type}', ['as'=>'get_result','uses'=> 'LottoController@getResults']);
});

/**
 * Callback API
 */
Route::group(['middleware' => 'web', 'prefix' => 'callback', 'as' => 'callback.', 'namespace' => 'Callback'], function()
{
    Route::any('/{game}', ['uses'=> 'AppCallbackController@index']);
});

Route::group(['middleware' => 'web', 'prefix' => 'statements', 'as' => 'statements.', 'namespace' => 'Statements'], function()
{
    Route::post('push/{number}', ['as'=>'push','uses'=> 'StatementsController@push']);
});

/**
 * For Marketing
*/
Route::group(['middleware' => 'web', 'prefix' => 'marketing', 'as' => 'marketing.', 'namespace' => 'Marketing'], function()
{
    Route::get('search-members', ['as'=>'search-members','uses'=> 'MarketingController@searchMembers']);
});

/**
 * For Event
 */
Route::group(['middleware' => 'events', 'prefix' => 'events', 'as' => 'events.', 'namespace' => 'Events'], function()
{
    Route::get('gen-username', ['as'=>'gen-username','uses'=> 'EventsController@genUsername']);
    Route::get('transfer-form', ['as'=>'transfer-form','uses'=> 'EventsController@transferForm']);
    Route::get('demo200', ['as'=>'demo200','uses'=> 'EventsController@freeDemo200']);

    Route::post('transfer-credit', ['as'=>'transfer-credit','uses'=> 'EventsController@transferCredit']);
});

/**
 * Route Domain API
*/
Route::domain('api.hiwpn.com')->group(function () {

    Route::get('/', function(){
        return "Access Deny!";
    });

    Route::group(['middleware' => 'agentapi', 'prefix' => 'api', 'as' => 'api.', 'namespace' => 'AgentsApi'], function()
    {
        Route::group(['prefix' => 'agent', 'as' => 'agent.'], function()
        {
            Route::post('/', ['uses'=> 'AgentsController@action'])->name('agent');
        });
        Route::group(['prefix' => 'member', 'as' => 'member.'], function()
        {
            Route::post('/', ['uses'=> 'MembersController@action'])->name('member');
        });
    });

});


Route::get('/', 'Auth\LoginController@toLogin');

Auth::routes();
