<?php

Route::group(['middleware' => ['web','permission:report.transferlogs.browse'], 'prefix'=>'report', 'as'=>'report.', 'namespace' => 'Modules\Report\TransferLogs\Http\Controllers'], function()
{
    Route::resource('transferlogs', 'TransferLogsController');
});
