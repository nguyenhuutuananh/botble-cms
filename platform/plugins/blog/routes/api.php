<?php

Route::group([
    'middleware' => 'api',
    'prefix'     => 'api/v1',
    'namespace'  => 'Botble\Blog\Http\Controllers\API',
], function () {

    Route::get('search', 'PostController@getSearch')->name('public.api.search');
    Route::get('posts', 'PostController@index');
    Route::get('categories', 'CategoryController@index');
    Route::get('tags', 'TagController@index');

});
