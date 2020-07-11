<?php

Route::group([
    'middleware' => ['web', 'permission:documents.browse'],
    'prefix' => 'documents',
    'as' => 'documents.',
    'namespace' => 'Modules\Documents\Http\Controllers'
], function () {
    Route::get('/', 'DocumentsController@indexRedirect');

    Route::group(['middleware' => ['web', 'permission:documents.settings']], function () {
        Route::resource('category', 'Settings\DocumentCategoryController');

        Route::resource('status', 'Settings\DocumentStatusController');

        Route::resource('type', 'Settings\DocumentTypeController');
    });

    Route::resource('documents', 'DocumentsController');
});
