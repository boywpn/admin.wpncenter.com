<?php

Route::group(
    ['middleware' => ['web', 'permission:servicecontracts.browse'], 'prefix' => 'servicecontracts', 'as' => 'servicecontracts.', 'namespace' => 'Modules\ServiceContracts\Http\Controllers'],
    function () {
        Route::get('/', 'ServiceContractsController@indexRedirect');

        Route::group(['middleware' => ['web', 'permission:servicecontracts.settings']], function () {
            Route::resource('priority', 'Settings\PriorityController');
            Route::resource('status', 'Settings\StatusController');
        });

        Route::resource('servicecontracts', 'ServiceContractsController');

        Route::get('servicecontracts/documents-selection/{entityId}', ['as'=>'documents.selection','uses'=> 'Tabs\ServiceContractsDocumentsController@selection']);
        Route::get('servicecontracts/documents-linked/{entityId}', ['as'=>'documents.linked','uses'=> 'Tabs\ServiceContractsDocumentsController@linked']);
        Route::post('servicecontracts/documents-unlink', ['as'=>'documents.unlink','uses'=> 'Tabs\ServiceContractsDocumentsController@unlink']);
        Route::post('servicecontracts/documents-link', ['as'=>'documents.link','uses'=> 'Tabs\ServiceContractsDocumentsController@link']);
    }
);
