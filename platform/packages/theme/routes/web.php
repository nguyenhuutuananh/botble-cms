<?php

use Botble\Theme\Events\ThemeRoutingAfterEvent;
use Botble\Theme\Events\ThemeRoutingBeforeEvent;

Route::group(['namespace' => 'Botble\Theme\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'theme'], function () {
            Route::get('', [
                'as'   => 'theme.index',
                'uses' => 'ThemeController@index',
            ]);

            Route::post('active', [
                'as'         => 'theme.active',
                'uses'       => 'ThemeController@postActivateTheme',
                'permission' => 'theme.index',
            ]);

            Route::post('remove', [
                'as'         => 'theme.remove',
                'uses'       => 'ThemeController@postRemoveTheme',
                'middleware' => 'preventDemo',
                'permission' => 'theme.index',
            ]);
        });

        Route::group(['prefix' => 'theme/options'], function () {
            Route::get('', [
                'as'   => 'theme.options',
                'uses' => 'ThemeController@getOptions',
            ]);

            Route::post('', [
                'as'   => 'theme.options',
                'uses' => 'ThemeController@postUpdate',
            ]);
        });

        Route::group(['prefix' => 'theme/custom-css'], function () {
            Route::get('', [
                'as'   => 'theme.custom-css',
                'uses' => 'ThemeController@getCustomCss',
            ]);

            Route::post('', [
                'as'         => 'theme.custom-css.post',
                'uses'       => 'ThemeController@postCustomCss',
                'permission' => 'theme.custom-css',
            ]);
        });
    });

    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        event(new ThemeRoutingBeforeEvent);

        Route::get('sitemap.xml', [
            'as'   => 'public.sitemap',
            'uses' => 'PublicController@getSiteMap',
        ]);

        Route::get('{slug?}' . config('core.base.general.public_single_ending_url'), [
            'as'   => 'public.single',
            'uses' => 'PublicController@getView',
        ]);

        event(new ThemeRoutingAfterEvent);
    });
});
