<?php

Route::group(['middleware' => ['web','permission:payments.browse'],'prefix'=>'payments','as'=>'payments.', 'namespace' => 'Modules\Payments\Http\Controllers'], function () {
    Route::get('/', 'PaymentsController@indexRedirect');

    Route::group(['middleware' => ['web','permission:payments.settings']], function () {
        Route::resource('category', 'Settings\PaymentCategoryController');

        Route::resource('status', 'Settings\PaymentStatusController');

        Route::resource('paymentmethod', 'Settings\PaymentPaymentMethodController');
    });

    Route::resource('payments', 'PaymentsController');

    Route::post('payments/import', ['as'=>'payments.import','uses'=> 'PaymentsController@import']);
    Route::post('payments/import_process', ['as'=>'payments.import.process','uses'=> 'PaymentsController@importProcess']);
});
