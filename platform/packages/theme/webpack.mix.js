let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

const resourcePath = 'platform/packages/theme';
const publicPath = 'public/vendor/core';

mix
    .js(resourcePath + '/resources/assets/js/app_modules/custom-css.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/custom-css.js', resourcePath + '/public/js/app_modules')

    .js(resourcePath + '/resources/assets/js/app_modules/theme-options.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/theme-options.js', resourcePath + '/public/js/app_modules')

    .js(resourcePath + '/resources/assets/js/app_modules/theme.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/theme.js', resourcePath + '/public/js/app_modules')

    .sass(resourcePath + '/resources/assets/sass/custom-css.scss', publicPath + '/css')
    .copy(publicPath + '/css/custom-css.css', resourcePath + '/public/css');
