<?php

Route::group(['namespace' => 'Botble\CustomField\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {

        Route::resource('custom-fields', 'CustomFieldController');

        Route::group(['prefix' => 'custom-fields'], function () {
            Route::delete('items/destroy', [
                'as'         => 'custom-fields.deletes',
                'uses'       => 'CustomFieldController@deletes',
                'permission' => 'custom-fields.destroy',
            ]);

            Route::get('export/{id?}', [
                'uses'       => 'CustomFieldController@getExport',
                'as'         => 'custom-fields.export',
                'permission' => 'custom-fields.index',
            ]);

            Route::post('import', [
                'uses'       => 'CustomFieldController@postImport',
                'as'         => 'custom-fields.import',
                'permission' => 'custom-fields.index',
            ]);
        });
    });
});
