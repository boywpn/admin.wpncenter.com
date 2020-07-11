<?php

Route::group(['middleware' => ['web','permission:leads.browse'],'prefix'=>'leads','as'=>'leads.', 'namespace' => 'Modules\Leads\Http\Controllers'], function () {
    Route::get('/','LeadsController@indexRedirect');

    Route::group(['middleware' => ['web','permission:leads.settings']], function () {
        Route::resource('status', 'Settings\LeadStatusController');

        Route::resource('source', 'Settings\LeadSourceController');

        Route::resource('industry', 'Settings\LeadIndustryController');

        Route::resource('rating', 'Settings\LeadRatingController');
    });

    Route::resource('leads', 'LeadsController');

    Route::get('leads/convert_to_contact/{id}', ['as'=>'leads.convert.to.contact','uses'=> 'LeadsController@convertToContact']);

    Route::post('leads/import', ['as'=>'leads.import','uses'=> 'LeadsController@import']);
    Route::post('leads/import_process', ['as'=>'leads.import.process','uses'=> 'LeadsController@importProcess']);


    Route::get('leads/leademails-selection/{entityId}', ['as'=>'leademails.selection','uses'=> 'Tabs\LeadEmailsController@selection']);
    Route::get('leads/leademails-linked/{entityId}', ['as'=>'leademails.linked','uses'=> 'Tabs\LeadEmailsController@linked']);
    Route::post('leads/leademails-unlink', ['as'=>'leademails.unlink','uses'=> 'Tabs\LeadEmailsController@unlink']);
    Route::post('leads/leademails-delete', ['as'=>'leademails.delete','uses'=> 'Tabs\LeadEmailsController@delete']);
    Route::post('leads/leademails-link', ['as'=>'leademails.link','uses'=> 'Tabs\LeadEmailsController@link']);


    Route::get('leads/calls-selection/{entityId}', ['as'=>'calls.selection','uses'=> 'Tabs\LeadCallsController@selection']);
    Route::get('leads/calls-linked/{entityId}', ['as'=>'calls.linked','uses'=> 'Tabs\LeadCallsController@linked']);
    Route::post('leads/calls-unlink', ['as'=>'calls.unlink','uses'=> 'Tabs\LeadCallsController@unlink']);
    Route::post('leads/calls-link', ['as'=>'calls.link','uses'=> 'Tabs\LeadCallsController@link']);

    Route::get('leads/documents-selection/{entityId}', ['as'=>'documents.selection','uses'=> 'Tabs\LeadDocumentsController@selection']);
    Route::get('leads/documents-linked/{entityId}', ['as'=>'documents.linked','uses'=> 'Tabs\LeadDocumentsController@linked']);
    Route::post('leads/documents-unlink', ['as'=>'documents.unlink','uses'=> 'Tabs\LeadDocumentsController@unlink']);
    Route::post('leads/documents-link', ['as'=>'documents.link','uses'=> 'Tabs\LeadDocumentsController@link']);

    Route::get('leads/campaigns-selection/{entityId}', ['as'=>'campaigns.selection','uses'=> 'Tabs\LeadCampaignsController@selection']);
    Route::get('leads/campaigns-linked/{entityId}', ['as'=>'campaigns.linked','uses'=> 'Tabs\LeadCampaignsController@linked']);
    Route::post('leads/campaigns-unlink', ['as'=>'campaigns.unlink','uses'=> 'Tabs\LeadCampaignsController@unlink']);
    Route::post('leads/campaigns-link', ['as'=>'campaigns.link','uses'=> 'Tabs\LeadCampaignsController@link']);

    Route::get('leads/products-selection/{entityId}', ['as'=>'products.selection','uses'=> 'Tabs\LeadProductsController@selection']);
    Route::get('leads/products-linked/{entityId}', ['as'=>'products.linked','uses'=> 'Tabs\LeadProductsController@linked']);
    Route::post('leads/products-unlink', ['as'=>'products.unlink','uses'=> 'Tabs\LeadProductsController@unlink']);
    Route::post('leads/products-link', ['as'=>'products.link','uses'=> 'Tabs\LeadProductsController@link']);
});
