<?php

Route::group(['middleware' => ['web','permission:core.promotions.browse'], 'prefix'=>'core', 'as'=>'core.', 'namespace' => 'Modules\Core\Promotions\Http\Controllers'], function () {
    Route::resource('promotions', 'PromotionsController');
});
