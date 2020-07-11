<?php

Route::group(['middleware' => ['web','permission:settings.access'], 'prefix' => 'settings','as'=>'settings.', 'namespace' => 'Modules\Platform\Companies\Http\Controllers'], function () {

    Route::resource('companies', 'CompanyController', []);

    Route::get('switch-context/{id}','CompanyContextController@switchCompany')->name('companies.switch-context');

    Route::get('drop-context', 'CompanyContextController@dropContext')->name('companies.drop-conext');

});
