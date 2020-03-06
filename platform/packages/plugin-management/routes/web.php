<?php

Route::group(['namespace' => 'Botble\PluginManagement\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'plugins'], function () {
            Route::get('', [
                'as'   => 'plugins.index',
                'uses' => 'PluginManagementController@index',
            ]);

            Route::get('change', [
                'as'         => 'plugins.change.status',
                'uses'       => 'PluginManagementController@update',
                'middleware' => 'preventDemo',
                'permission' => 'plugins.index',
            ]);

            Route::post('remove', [
                'as'         => 'plugins.remove',
                'uses'       => 'PluginManagementController@destroy',
                'middleware' => 'preventDemo',
                'permission' => 'plugins.index',
            ]);
        });
    });
});
