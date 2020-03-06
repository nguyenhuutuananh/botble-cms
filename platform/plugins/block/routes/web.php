<?php

Route::group(['namespace' => 'Botble\Block\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {

        Route::resource('blocks', 'BlockController', ['names' => 'block']);

        Route::group(['prefix' => 'blocks'], function () {

            Route::delete('items/destroy', [
                'as'         => 'block.deletes',
                'uses'       => 'BlockController@deletes',
                'permission' => 'block.destroy',
            ]);
        });
    });
});
