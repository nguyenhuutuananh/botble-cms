<?php

Route::group(['namespace' => 'Botble\Blog\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {

        Route::resource('posts', 'PostController');
        Route::resource('categories', 'CategoryController');
        Route::resource('tags', 'TagController');

        Route::group(['prefix' => 'posts'], function () {
            Route::delete('items/destroy', [
                'as'         => 'posts.deletes',
                'uses'       => 'PostController@deletes',
                'permission' => 'posts.destroy',
            ]);

            Route::get('widgets/recent-posts', [
                'as'         => 'posts.widget.recent-posts',
                'uses'       => 'PostController@getWidgetRecentPosts',
                'permission' => 'posts.index',
            ]);
        });

        Route::group(['prefix' => 'categories'], function () {
            Route::delete('items/destroy', [
                'as'         => 'categories.deletes',
                'uses'       => 'CategoryController@deletes',
                'permission' => 'categories.destroy',
            ]);
        });

        Route::group(['prefix' => 'tags'], function () {
            Route::delete('items/destroy', [
                'as'         => 'tags.deletes',
                'uses'       => 'TagController@deletes',
                'permission' => 'tags.destroy',
            ]);

            Route::get('all', [
                'as'         => 'tags.all',
                'uses'       => 'TagController@getAllTags',
                'permission' => 'tags.index',
            ]);
        });
    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
            Route::get('search', [
                'as'   => 'public.search',
                'uses' => 'PublicController@getSearch',
            ]);

            Route::get('author/{slug}', [
                'as'   => 'public.author',
                'uses' => 'PublicController@getAuthor',
            ]);

            Route::get('tag/{slug}', [
                'as'   => 'public.tag',
                'uses' => 'PublicController@getTag',
            ]);
        });
    }
});
