<?php

Route::group(['namespace' => 'Botble\Contact\Http\Controllers', 'middleware' => 'web'], function () {
    Route::post('send-contact', [
        'as'   => 'public.send.contact',
        'uses' => 'PublicController@postSendContact',
    ]);

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {

        Route::resource('contacts', 'ContactController')->except(['create', 'store']);

        Route::group(['prefix' => 'contacts'], function () {
            Route::delete('items/destroy', [
                'as'         => 'contacts.deletes',
                'uses'       => 'ContactController@deletes',
                'permission' => 'contacts.destroy',
            ]);

            Route::post('reply/{id}', [
                'as'   => 'contacts.reply',
                'uses' => 'ContactController@postReply',
            ]);
        });
    });
});
