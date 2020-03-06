<?php

Route::group(['namespace' => 'Botble\RequestLog\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {

        Route::resource('request-logs', 'RequestLogController', ['names' => 'request-log'])
            ->only(['index', 'destroy']);

        Route::group(['prefix' => 'request-logs'], function () {

            Route::get('widgets/request-errors', [
                'as'         => 'request-log.widget.request-errors',
                'uses'       => 'RequestLogController@getWidgetRequestErrors',
                'permission' => 'request-log.index',
            ]);

            Route::delete('items/destroy', [
                'as'         => 'request-log.deletes',
                'uses'       => 'RequestLogController@deletes',
                'permission' => 'request-log.destroy',
            ]);

            Route::get('items/empty', [
                'as'         => 'request-log.empty',
                'uses'       => 'RequestLogController@deleteAll',
                'permission' => 'request-log.destroy',
            ]);
        });
    });
});
