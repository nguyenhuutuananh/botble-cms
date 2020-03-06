<?php

if (defined('THEME_MODULE_SCREEN_NAME')) {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

        Route::group([
            'namespace'  => 'Botble\Blog\Http\Controllers',
            'middleware' => ['web', 'member'],
            'as'         => 'public.member.',
        ], function () {
            Route::group([
                'prefix' => 'account/posts',
            ], function () {

                Route::get('', [
                    'as'   => 'posts.index',
                    'uses' => 'MemberPostController@index',
                ]);

                Route::get('create', [
                    'as'   => 'posts.create',
                    'uses' => 'MemberPostController@create',
                ]);

                Route::post('create', [
                    'as'   => 'posts.create',
                    'uses' => 'MemberPostController@store',
                ]);

                Route::get('edit/{id}', [
                    'as'   => 'posts.edit',
                    'uses' => 'MemberPostController@edit',
                ]);

                Route::post('edit/{id}', [
                    'as'   => 'posts.edit',
                    'uses' => 'MemberPostController@update',
                ]);

            });

            Route::group(['prefix' => 'ajax/members'], function () {
                Route::delete('delete/{id}', [
                    'as'   => 'posts.destroy',
                    'uses' => 'MemberPostController@delete',
                ]);
            });
        });

    });
}