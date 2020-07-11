<?php

Route::group(['middleware' => ['web','permission:core.bankspartners.browse'], 'prefix'=>'core', 'as'=>'core.', 'namespace' => 'Modules\Core\BanksPartners\Http\Controllers'], function () {

    Route::resource('bankspartners', 'BanksPartnersController');

});
