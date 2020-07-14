<?php

Route::group(['middleware' => ['web','permission:job.jobs.browse'], 'prefix'=>'job', 'as'=>'job.', 'namespace' => 'Modules\Job\Jobs\Http\Controllers'], function () {

//    Route::group(['middleware' => ['web','permission:job.jobs.settings'], 'prefix'=>'jobs', 'as'=>'jobs.'], function () {
//        Route::resource('status', 'Settings\JobsStatusController');
//    });

    Route::get('jobs/search/{type}', ['as'=>'jobs.search.members', 'uses' => 'JobsController@searchMember']);
    Route::get('jobs/deposit', ['as'=>'jobs.deposit', 'uses' => 'DepositController@index']);

    Route::get('jobs/list-table/{type}', ['as'=>'jobs.list-table', 'uses' => 'JobsController@getTableJobs']);

    Route::get('jobs/get-job/{id}', ['as'=>'jobs.get-job', 'uses' => 'JobsController@getJobByID']);
    Route::get('jobs/view-job/{id}', ['as'=>'jobs.view-job', 'uses' => 'JobsController@renderJobByID']);
    Route::get('jobs/lock-job/{id}', ['as'=>'jobs.lock-job', 'uses' => 'JobsController@lockJobByID']);

    Route::post('jobs/cancel/{id}', ['as'=>'jobs.cancel', 'uses' => 'JobsController@cancel']);
    Route::post('jobs/approve/{id}', ['as'=>'jobs.approve', 'uses' => 'JobsController@approve']);

    Route::get('jobs/check-statement/{id}', ['as'=>'jobs.check-statement', 'uses' => 'JobsController@checkStatementByJobID']);

    Route::get('jobs/withdraw', ['as'=>'jobs.withdraw', 'uses' => 'WithdrawController@index']);

    Route::resource('jobs', 'JobsController');
    Route::post('jobs/new', ['as'=>'jobs.new', 'uses' => 'JobsController@formCreate']);

});
