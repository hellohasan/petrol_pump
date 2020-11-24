const mix = require('laravel-mix');

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

/*mix.js('resources/js/app.js', 'public/js');*/

mix.styles([
    'public/assets/admin/css/bootstrap.css',
    'public/assets/admin/fonts/feather/style.css',
    'public/assets/admin/fonts/font-awesome/css/font-awesome.css',
    'public/assets/admin/css/pace.css',
    'public/assets/admin/css/bootstrap-extended.css',
    'public/assets/admin/css/colors.css',
    'public/assets/admin/css/components.css',
    'public/assets/admin/css/vertical-menu.css',
    'public/assets/admin/css/palette-gradient.css',
    'public/assets/admin/css/style.css'
], 'public/assets/admin/css/backend.css')
    .scripts([
        'public/assets/admin/js/vendors.min.js',
        'public/assets/admin/js/app-menu.js',
        'public/assets/admin/js/app.js',
    ],'public/assets/admin/js/backend.js');
