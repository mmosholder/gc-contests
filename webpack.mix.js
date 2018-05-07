const { mix } = require('laravel-mix');

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

mix.js('src/main.js', 'public/js/app.js')
    .copy('public/js/app.js', 'wp-content/themes/relia/library/js/app.js')
   .sass('src/scss/app.scss', 'public/css')
   .copy('public/css/app.css', 'wp-content/themes/relia/library/css/app.css');
