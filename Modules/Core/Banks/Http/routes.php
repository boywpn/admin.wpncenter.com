<?php

Route::group(['middleware' => ['web','permission:core.banks.browse'], 'prefix'=>'core', 'as'=>'core.', 'namespace' => 'Modules\Core\Banks\Http\Controllers'], function () {

    Route::resource('banks', 'BanksController');

});
