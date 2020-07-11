<?php

Route::group(['middleware' => ['web', 'permission:contacts.browse'], 'prefix' => 'contacts', 'as' => 'contacts.', 'namespace' => 'Modules\Contacts\Http\Controllers'], function () {
    Route::get('/', 'ContactsController@indexRedirect');

    Route::group(['middleware' => ['web', 'permission:contacts.settings']], function () {
        Route::resource('status', 'Settings\StatusController');
        Route::resource('source', 'Settings\SourceController');
    });

    Route::resource('contacts', 'ContactsController');

    Route::post('contacts/import', ['as'=>'contacts.import','uses'=> 'ContactsController@import']);
    Route::post('contacts/import_process', ['as'=>'contacts.import.process','uses'=> 'ContactsController@importProcess']);

    Route::get('contacts/contactemails-selection/{entityId}', ['as'=>'contactemails.selection','uses'=> 'Tabs\ContactEmailsController@selection']);
    Route::get('contacts/contactemails-linked/{entityId}', ['as'=>'contactemails.linked','uses'=> 'Tabs\ContactEmailsController@linked']);
    Route::post('contacts/contactemails-unlink', ['as'=>'contactemails.unlink','uses'=> 'Tabs\ContactEmailsController@unlink']);
    Route::post('contacts/contactemails-delete', ['as'=>'contactemails.delete','uses'=> 'Tabs\ContactEmailsController@delete']);
    Route::post('contacts/contactemails-link', ['as'=>'contactemails.link','uses'=> 'Tabs\ContactCampaignsController@link']);

    Route::get('contacts/campaigns-selection/{entityId}', ['as'=>'campaigns.selection','uses'=> 'Tabs\ContactCampaignsController@selection']);
    Route::get('contacts/campaigns-linked/{entityId}', ['as'=>'campaigns.linked','uses'=> 'Tabs\ContactCampaignsController@linked']);
    Route::post('contacts/campaigns-unlink', ['as'=>'campaigns.unlink','uses'=> 'Tabs\ContactCampaignsController@unlink']);
    Route::post('contacts/campaigns-link', ['as'=>'campaigns.link','uses'=> 'Tabs\ContactCampaignsController@link']);


    Route::get('contacts/calls-selection/{entityId}', ['as'=>'calls.selection','uses'=> 'Tabs\ContactCallsController@selection']);
    Route::get('contacts/calls-linked/{entityId}', ['as'=>'calls.linked','uses'=> 'Tabs\ContactCallsController@linked']);
    Route::post('contacts/calls-unlink', ['as'=>'calls.unlink','uses'=> 'Tabs\ContactCallsController@unlink']);
    Route::post('contacts/calls-link', ['as'=>'calls.link','uses'=> 'Tabs\ContactCallsController@link']);


    Route::get('contacts/deals-selection/{entityId}', ['as'=>'deals.selection','uses'=> 'Tabs\ContactDealsController@selection']);
    Route::get('contacts/deals-linked/{entityId}', ['as'=>'deals.linked','uses'=> 'Tabs\ContactDealsController@linked']);
    Route::post('contacts/deals-unlink', ['as'=>'deals.unlink','uses'=> 'Tabs\ContactDealsController@unlink']);
    Route::post('contacts/deals-link', ['as'=>'deals.link','uses'=> 'Tabs\ContactDealsController@link']);

    Route::get('contacts/tickets-selection/{entityId}', ['as'=>'tickets.selection','uses'=> 'Tabs\ContactTicketsController@selection']);
    Route::get('contacts/tickets-linked/{entityId}', ['as'=>'tickets.linked','uses'=> 'Tabs\ContactTicketsController@linked']);
    Route::post('contacts/tickets-unlink', ['as'=>'tickets.unlink','uses'=> 'Tabs\ContactTicketsController@unlink']);
    Route::post('contacts/tickets-link', ['as'=>'tickets.link','uses'=> 'Tabs\ContactTicketsController@link']);

    Route::get('contacts/assets-selection/{entityId}', ['as'=>'assets.selection','uses'=> 'Tabs\ContactAssetsController@selection']);
    Route::get('contacts/assets-linked/{entityId}', ['as'=>'assets.linked','uses'=> 'Tabs\ContactAssetsController@linked']);
    Route::post('contacts/assets-unlink', ['as'=>'assets.unlink','uses'=> 'Tabs\ContactAssetsController@unlink']);
    Route::post('contacts/assets-link', ['as'=>'assets.link','uses'=> 'Tabs\ContactAssetsController@link']);


    Route::get('contacts/orders-selection/{entityId}', ['as'=>'orders.selection','uses'=> 'Tabs\ContactOrdersController@selection']);
    Route::get('contacts/orders-linked/{entityId}', ['as'=>'orders.linked','uses'=> 'Tabs\ContactOrdersController@linked']);
    Route::post('contacts/orders-unlink', ['as'=>'orders.unlink','uses'=> 'Tabs\ContactOrdersController@unlink']);
    Route::post('contacts/orders-link', ['as'=>'orders.link','uses'=> 'Tabs\ContactOrdersController@link']);

    Route::get('contacts/invoices-selection/{entityId}', ['as'=>'invoices.selection','uses'=> 'Tabs\ContactInvoicesController@selection']);
    Route::get('contacts/invoices-linked/{entityId}', ['as'=>'invoices.linked','uses'=> 'Tabs\ContactInvoicesController@linked']);
    Route::post('contacts/invoices-unlink', ['as'=>'invoices.unlink','uses'=> 'Tabs\ContactInvoicesController@unlink']);
    Route::post('contacts/invoices-link', ['as'=>'invoices.link','uses'=> 'Tabs\ContactInvoicesController@link']);

    Route::get('contacts/quotes-selection/{entityId}', ['as'=>'quotes.selection','uses'=> 'Tabs\ContactQuotesController@selection']);
    Route::get('contacts/quotes-linked/{entityId}', ['as'=>'quotes.linked','uses'=> 'Tabs\ContactQuotesController@linked']);
    Route::post('contacts/quotes-unlink', ['as'=>'quotes.unlink','uses'=> 'Tabs\ContactQuotesController@unlink']);
    Route::post('contacts/quotes-link', ['as'=>'quotes.link','uses'=> 'Tabs\ContactQuotesController@link']);


    Route::get('contacts/products-selection/{entityId}', ['as'=>'products.selection','uses'=> 'Tabs\ContactProductsController@selection']);
    Route::get('contacts/products-linked/{entityId}', ['as'=>'products.linked','uses'=> 'Tabs\ContactProductsController@linked']);
    Route::post('contacts/products-unlink', ['as'=>'products.unlink','uses'=> 'Tabs\ContactProductsController@unlink']);
    Route::post('contacts/products-link', ['as'=>'products.link','uses'=> 'Tabs\ContactProductsController@link']);

    Route::get('contacts/purchased-products-selection/{entityId}', ['as'=>'purchased_products.selection','uses'=> 'Tabs\ContactPurchasedProductsController@selection']);
    Route::get('contacts/purchased-products-linked/{entityId}', ['as'=>'purchased_products.linked','uses'=> 'Tabs\ContactPurchasedProductsController@linked']);
    Route::post('contacts/purchased-products-unlink', ['as'=>'purchased_products.unlink','uses'=> 'Tabs\ContactPurchasedProductsController@unlink']);
    Route::post('contacts/purchased-products-link', ['as'=>'purchased_products.link','uses'=> 'Tabs\ContactPurchasedProductsController@link']);

    Route::get('contacts/documents-selection/{entityId}', ['as'=>'documents.selection','uses'=> 'Tabs\ContactDocumentsController@selection']);
    Route::get('contacts/documents-linked/{entityId}', ['as'=>'documents.linked','uses'=> 'Tabs\ContactDocumentsController@linked']);
    Route::post('contacts/documents-unlink', ['as'=>'documents.unlink','uses'=> 'Tabs\ContactDocumentsController@unlink']);
    Route::post('contacts/documents-link', ['as'=>'documents.link','uses'=> 'Tabs\ContactDocumentsController@link']);


});
