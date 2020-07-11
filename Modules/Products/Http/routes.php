<?php

Route::group(['middleware' => ['web','permission:products.browse'],'prefix'=>'products','as'=>'products.', 'namespace' => 'Modules\Products\Http\Controllers'], function () {
    Route::get('/', 'ProductsController@indexRedirect');

    Route::get('products/price_list-selection/{entityId}', ['as'=>'price_list.selection','uses'=> 'Tabs\PriceListTabController@selection']);
    Route::get('products/price_list-linked/{entityId}', ['as'=>'price_list.linked','uses'=> 'Tabs\PriceListTabController@linked']);
    Route::post('products/price_list-unlink', ['as'=>'price_list.unlink','uses'=> 'Tabs\PriceListTabController@unlink']);
    Route::post('products/price_list-delete', ['as'=>'price_list.delete','uses'=> 'Tabs\PriceListTabController@delete']);
    Route::post('products/price_list-link', ['as'=>'price_list.link','uses'=> 'Tabs\PriceListTabController@link']);


    Route::group(['middleware' => ['web','permission:products.settings']], function () {
        Route::resource('type', 'Settings\TypeController');
        Route::resource('category', 'Settings\CategoryController');
    });

    Route::resource('products', 'ProductsController');
    Route::resource('price_list', 'PriceListController');

    Route::post('load-price-list', 'PriceListController@loadPriceList');
});
