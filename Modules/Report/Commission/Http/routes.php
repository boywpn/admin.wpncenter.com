<?php

Route::group(['middleware' => 'web', 'prefix' => 'report/commission', 'namespace' => 'Modules\Report\Commission\Http\Controllers'], function()
{
    Route::get('/', 'CommissionController@index');

    Route::get('keep_transfer/{game}', 'TransferCommissionController@keep_transfer');
    Route::get('keep_transfer_user/{game}/{username}', 'TransferCommissionController@keepTransferByUser');

    Route::get('transfer/{game}', 'TransferCommissionController@transfer');
});
