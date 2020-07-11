<?php

Route::group(['middleware' => ['web','permission:settings.access'], 'prefix' => 'settings/menu_manager','as'=>'settings.menu_manager.', 'namespace' => 'Modules\Platform\MenuManager\Http\Controllers'], function () {
    Route::get('/', ['as'=>'index','uses'=>'MenuManagerController@index' ]);

    Route::get('/create_element', ['as'=>'create_element','uses'=>'MenuManagerController@createMenuElement' ]);

    Route::get('/delete-element/{id}', ['as'=>'delete','uses'=>'MenuManagerController@deleteElement' ]);

    Route::get('/get_menu_element/{id}', ['as'=>'get_menu_element','uses'=>'MenuManagerController@getMenuElement' ]);

    Route::post('/update_order', ['as'=>'update_order','uses'=>'MenuManagerController@updateOrder' ]);

    Route::post('/save_menu_element', ['as'=>'save_menu_element','uses'=>'MenuManagerController@saveElement' ]);
});
