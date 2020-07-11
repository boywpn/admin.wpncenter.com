<?php

Route::group(['middleware' => ['web','permission:games.browse'], 'prefix' => 'core', 'as' => 'core.', 'namespace' => 'Modules\Core\Games\Http\Controllers'], function()
{
    Route::resource('games', 'GamesController');

    Route::group(['middleware' => ['web','permission:games-types.browse'], 'prefix'=>'games', 'as'=>'games.'], function () {
        Route::resource('types', 'GamesTypesController');

        Route::get('types/types-selection/{entityId}', ['as'=>'types.selection','uses'=> 'Tabs\GamesTypesControllerTab@selection']);
        Route::get('types/types-linked/{entityId}', ['as'=>'types.linked','uses'=> 'Tabs\GamesTypesControllerTab@linked']);
        Route::post('types/types-unlink', ['as'=>'types.unlink','uses'=> 'Tabs\GamesTypesControllerTab@unlink']);
        Route::post('types/types-link', ['as'=>'types.link','uses'=> 'Tabs\GamesTypesControllerTab@link']);
    });

});

Route::group(['middleware' => ['web','permission:games-types.browse'], 'prefix' => 'core', 'as' => 'core.', 'namespace' => 'Modules\Core\Games\Http\Controllers'], function()
{
    Route::resource('games-types', 'GamesTypesController');
});
