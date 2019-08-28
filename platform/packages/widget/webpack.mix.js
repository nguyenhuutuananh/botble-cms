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

const resourcePath = 'platform/packages/widget';
const publicPath = 'public/vendor/core';

mix
    .js(resourcePath + '/resources/assets/js/app_modules/widget.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/widget.js', resourcePath + '/public/js/app_modules');
