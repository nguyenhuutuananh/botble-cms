<?php

Route::group(['namespace' => 'Botble\Menu\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {

        Route::resource('menus', 'MenuController');

        Route::group(['prefix' => 'menus'], function () {
            Route::delete('items/destroy', [
                'as'         => 'menus.deletes',
                'uses'       => 'MenuController@deletes',
                'permission' => 'menus.destroy',
            ]);
        });
    });
});
