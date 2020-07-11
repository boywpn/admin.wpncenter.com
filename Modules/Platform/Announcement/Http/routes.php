<?php

Route::group(['middleware' => ['web','permission:settings.access'], 'prefix' => 'settings/announcement', 'namespace' => 'Modules\Platform\Announcement\Http\Controllers'], function () {
    Route::get('/', ['as'=>'settings.announcement','uses'=>'AnnouncementController@index' ]);
    Route::post('/', ['as'=>'settings.announcement','uses'=>'AnnouncementController@saveAnnouncement' ]);
});
