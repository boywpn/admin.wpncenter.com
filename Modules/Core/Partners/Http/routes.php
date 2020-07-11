<?php

Route::group(['middleware' => ['web','permission:partners.browse'], 'prefix' => 'core', 'as' => 'core.', 'namespace' => 'Modules\Core\Partners\Http\Controllers'], function()
{
    Route::resource('partners', 'PartnersController');
});
