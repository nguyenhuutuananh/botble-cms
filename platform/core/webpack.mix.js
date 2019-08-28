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

const resourcePath = 'platform/core';
const publicPath = 'public/vendor/core';

let fs = require('fs');

let themePath = resourcePath + '/base/resources/assets/sass/base/themes';
let paths = fs.readdirSync(themePath);
for (let i = 0; i < paths.length; i++) {
    if (paths[i].indexOf('.scss') > 0 && paths[i].charAt(0) !== '_') {
        let file = themePath + '/' + paths[i];
        mix.sass(file, publicPath + '/css/themes')
            .copy(publicPath + '/css/themes/' + paths[i].replace('.scss', '.css'), resourcePath + '/base/public/css/themes');
    }
}

mix
    .sass(resourcePath + '/base/resources/assets/sass/core.scss', publicPath + '/css')
    .copy(publicPath + '/css/core.css', resourcePath + '/base/public/css')
    .sass(resourcePath + '/base/resources/assets/sass/custom/admin-bar.scss', publicPath + '/css')
    .copy(publicPath + '/css/admin-bar.css', resourcePath + '/base/public/css')
    .sass(resourcePath + '/base/resources/assets/sass/custom/system-info.scss', publicPath + '/css')
    .copy(publicPath + '/css/system-info.css', resourcePath + '/base/public/css')
    .sass(resourcePath + '/base/resources/assets/sass/custom/email.scss', publicPath + '/css')
    .copy(publicPath + '/css/email.css', resourcePath + '/base/public/css')

    .js(resourcePath + '/base/resources/assets/js/app.js', publicPath + '/js')
    .copy(publicPath + '/js/app.js', resourcePath + '/base/public/js')
    .js(resourcePath + '/base/resources/assets/js/core.js', publicPath + '/js')
    .copy(publicPath + '/js/core.js', resourcePath + '/base/public/js');

// Modules Core
mix
    .js(resourcePath + '/base/resources/assets/js/app_modules/editor.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/editor.js', resourcePath + '/base/public/js/app_modules')
    .js(resourcePath + '/base/resources/assets/js/app_modules/plugin.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/plugin.js', resourcePath + '/base/public/js/app_modules')
    .js(resourcePath + '/base/resources/assets/js/app_modules/cache.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/cache.js', resourcePath + '/base/public/js/app_modules')
    .js(resourcePath + '/base/resources/assets/js/app_modules/tags.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/tags.js', resourcePath + '/base/public/js/app_modules')
    .js(resourcePath + '/base/resources/assets/js/app_modules/system-info.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/system-info.js', resourcePath + '/base/public/js/app_modules')

    .js(resourcePath + '/setting/resources/assets/js/app_modules/setting.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/setting.js', resourcePath + '/setting/public/js/app_modules')
    .sass(resourcePath + '/setting/resources/assets/sass/setting.scss', publicPath + '/css')
    .copy(publicPath + '/css/setting.css', resourcePath + '/setting/public/css')

    .js(resourcePath + '/table/resources/assets/js/app_modules/table.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/table.js', resourcePath + '/table/public/js/app_modules')
    .js(resourcePath + '/table/resources/assets/js/app_modules/filter.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/filter.js', resourcePath + '/table/public/js/app_modules')
    .sass(resourcePath + '/table/resources/assets/sass/table.scss', publicPath + '/css/components')
    .copy(publicPath + '/css/components/table.css', resourcePath + '/table/public/css/components')

    .js(resourcePath + '/dashboard/resources/assets/js/app_modules/dashboard.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/dashboard.js', resourcePath + '/dashboard/public/js/app_modules')

    .js(resourcePath + '/acl/resources/assets/js/app_modules/profile.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/profile.js', resourcePath + '/acl/public/js/app_modules')
    .js(resourcePath + '/acl/resources/assets/js/app_modules/login.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/login.js', resourcePath + '/acl/public/js/app_modules')
    .js(resourcePath + '/acl/resources/assets/js/app_modules/role.js', publicPath + '/js/app_modules')
    .copy(publicPath + '/js/app_modules/role.js', resourcePath + '/acl/public/js/app_modules');

// Media
mix
    .sass(resourcePath + '/media/resources/assets/sass/media.scss', publicPath + '/media/css')
    .copy(publicPath + '/media/css/media.css', resourcePath + '/media/public/assets/css')
    .js(resourcePath + '/media/resources/assets/js/media.js', publicPath + '/media/js')
    .copy(publicPath + '/media/js/media.js', resourcePath + '/media/public/assets/js')
    .js(resourcePath + '/media/resources/assets/js/jquery.addMedia.js', publicPath + '/media/js')
    .copy(publicPath + '/media/js/jquery.addMedia.js', resourcePath + '/media/public/assets/js')
    .js(resourcePath + '/media/resources/assets/js/integrate.js', publicPath + '/media/js')
    .copy(publicPath + '/media/js/integrate.js', resourcePath + '/media/public/assets/js');

// JS Validation
mix
    .copy('./vendor/proengsoft/laravel-jsvalidation/public/js/jsvalidation.min.js', publicPath + '/js/app_modules/form-validation.js')
    .copy(publicPath + '/js/app_modules/form-validation.js', resourcePath + '/base/public/js/app_modules');

