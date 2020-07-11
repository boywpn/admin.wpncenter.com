<?php

Route::group(['middleware' => ['web', 'permission:accounts.browse'], 'prefix' => 'accounts', 'as' => 'accounts.', 'namespace' => 'Modules\Accounts\Http\Controllers'], function () {
    Route::get('/', 'AccountsController@indexRedirect');

    Route::group(['middleware' => ['web', 'permission:accounts.settings']], function () {
        Route::resource('type', 'Settings\TypeController');
        Route::resource('rating', 'Settings\RatingController');
        Route::resource('industry', 'Settings\IndustryController');
    });

    Route::resource('accounts', 'AccountsController');

    Route::post('accounts/import', ['as'=>'accounts.import','uses'=> 'AccountsController@import']);
    Route::post('accounts/import_process', ['as'=>'accounts.import.process','uses'=> 'AccountsController@importProcess']);



    Route::get('accounts/tickets-selection/{entityId}', ['as'=>'tickets.selection','uses'=> 'Tabs\AccountsTicketsController@selection']);
    Route::get('accounts/tickets-linked/{entityId}', ['as'=>'tickets.linked','uses'=> 'Tabs\AccountsTicketsController@linked']);
    Route::post('accounts/tickets-unlink', ['as'=>'tickets.unlink','uses'=> 'Tabs\AccountsTicketsController@unlink']);
    Route::post('accounts/tickets-link', ['as'=>'tickets.link','uses'=> 'Tabs\AccountsTicketsController@link']);

    Route::get('accounts/calls-selection/{entityId}', ['as'=>'calls.selection','uses'=> 'Tabs\AccountsCallsController@selection']);
    Route::get('accounts/calls-linked/{entityId}', ['as'=>'calls.linked','uses'=> 'Tabs\AccountsCallsController@linked']);
    Route::post('accounts/calls-unlink', ['as'=>'calls.unlink','uses'=> 'Tabs\AccountsCallsController@unlink']);
    Route::post('accounts/calls-link', ['as'=>'calls.link','uses'=> 'Tabs\AccountsCallsController@link']);

    Route::get('accounts/deals-selection/{entityId}', ['as'=>'deals.selection','uses'=> 'Tabs\AccountsDealsController@selection']);
    Route::get('accounts/deals-linked/{entityId}', ['as'=>'deals.linked','uses'=> 'Tabs\AccountsDealsController@linked']);
    Route::post('accounts/deals-unlink', ['as'=>'deals.unlink','uses'=> 'Tabs\AccountsDealsController@unlink']);
    Route::post('accounts/deals-link', ['as'=>'deals.link','uses'=> 'Tabs\AccountsDealsController@link']);

    Route::get('accounts/quotes-selection/{entityId}', ['as'=>'quotes.selection','uses'=> 'Tabs\AccountsQuotesController@selection']);
    Route::get('accounts/quotes-linked/{entityId}', ['as'=>'quotes.linked','uses'=> 'Tabs\AccountsQuotesController@linked']);
    Route::post('accounts/quotes-unlink', ['as'=>'quotes.unlink','uses'=> 'Tabs\AccountsQuotesController@unlink']);
    Route::post('accounts/quotes-link', ['as'=>'quotes.link','uses'=> 'Tabs\AccountsQuotesController@link']);

    Route::get('accounts/orders-selection/{entityId}', ['as'=>'orders.selection','uses'=> 'Tabs\AccountsOrdersController@selection']);
    Route::get('accounts/orders-linked/{entityId}', ['as'=>'orders.linked','uses'=> 'Tabs\AccountsOrdersController@linked']);
    Route::post('accounts/orders-unlink', ['as'=>'orders.unlink','uses'=> 'Tabs\AccountsOrdersController@unlink']);
    Route::post('accounts/orders-link', ['as'=>'orders.link','uses'=> 'Tabs\AccountsOrdersController@link']);

    Route::get('accounts/invoices-selection/{entityId}', ['as'=>'invoices.selection','uses'=> 'Tabs\AccountsInvoicesController@selection']);
    Route::get('accounts/invoices-linked/{entityId}', ['as'=>'invoices.linked','uses'=> 'Tabs\AccountsInvoicesController@linked']);
    Route::post('accounts/invoices-unlink', ['as'=>'invoices.unlink','uses'=> 'Tabs\AccountsInvoicesController@unlink']);
    Route::post('accounts/invoices-link', ['as'=>'invoices.link','uses'=> 'Tabs\AccountsInvoicesController@link']);

    Route::get('accounts/documents-selection/{entityId}', ['as'=>'documents.selection','uses'=> 'Tabs\AccountsDocumentsController@selection']);
    Route::get('accounts/documents-linked/{entityId}', ['as'=>'documents.linked','uses'=> 'Tabs\AccountsDocumentsController@linked']);
    Route::post('accounts/documents-unlink', ['as'=>'documents.unlink','uses'=> 'Tabs\AccountsDocumentsController@unlink']);
    Route::post('accounts/documents-link', ['as'=>'documents.link','uses'=> 'Tabs\AccountsDocumentsController@link']);

    Route::get('accounts/campaigns-selection/{entityId}', ['as'=>'campaigns.selection','uses'=> 'Tabs\AccountsCampaignsController@selection']);
    Route::get('accounts/campaigns-linked/{entityId}', ['as'=>'campaigns.linked','uses'=> 'Tabs\AccountsCampaignsController@linked']);
    Route::post('accounts/campaigns-unlink', ['as'=>'campaigns.unlink','uses'=> 'Tabs\AccountsCampaignsController@unlink']);
    Route::post('accounts/campaigns-link', ['as'=>'campaigns.link','uses'=> 'Tabs\AccountsCampaignsController@link']);

    Route::get('accounts/servicecontracts-selection/{entityId}', ['as'=>'servicecontracts.selection','uses'=> 'Tabs\AccountsServiceContractsController@selection']);
    Route::get('accounts/servicecontracts-linked/{entityId}', ['as'=>'servicecontracts.linked','uses'=> 'Tabs\AccountsServiceContractsController@linked']);
    Route::post('accounts/servicecontracts-unlink', ['as'=>'servicecontracts.unlink','uses'=> 'Tabs\AccountsServiceContractsController@unlink']);
    Route::post('accounts/servicecontracts-link', ['as'=>'servicecontracts.link','uses'=> 'Tabs\AccountsServiceContractsController@link']);

    Route::get('accounts/assets-selection/{entityId}', ['as'=>'assets.selection','uses'=> 'Tabs\AccountsAssetsController@selection']);
    Route::get('accounts/assets-linked/{entityId}', ['as'=>'assets.linked','uses'=> 'Tabs\AccountsAssetsController@linked']);
    Route::post('accounts/assets-unlink', ['as'=>'assets.unlink','uses'=> 'Tabs\AccountsAssetsController@unlink']);
    Route::post('accounts/assets-link', ['as'=>'assets.link','uses'=> 'Tabs\AccountsAssetsController@link']);

    Route::get('accounts/contacts-selection/{entityId}', ['as'=>'contacts.selection','uses'=> 'Tabs\AccountsContactsController@selection']);
    Route::get('accounts/contacts-linked/{entityId}', ['as'=>'contacts.linked','uses'=> 'Tabs\AccountsContactsController@linked']);
    Route::post('accounts/contacts-unlink', ['as'=>'contacts.unlink','uses'=> 'Tabs\AccountsContactsController@unlink']);
    Route::post('accounts/contacts-link', ['as'=>'contacts.link','uses'=> 'Tabs\AccountsContactsController@link']);
});
