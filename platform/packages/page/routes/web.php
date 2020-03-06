<?php

Route::group(['namespace' => 'Botble\Page\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {

        Route::resource('pages', 'PageController');

        Route::group(['prefix' => 'pages'], function () {
            Route::delete('items/destroy', [
                'as'         => 'pages.deletes',
                'uses'       => 'PageController@deletes',
                'permission' => 'pages.destroy',
            ]);
        });
    });
});
