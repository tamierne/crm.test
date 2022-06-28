const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

 mix.styles([
    'resources/admin/css/tailwind.css',
    'resources/admin/css/all.css',
    'resources/admin/css/emoji.css',
    ], 'public/assets/admin/css/styles.css');

mix.scripts([
    'resources/admin/js/Chart.bundle.js',
    ],'public/assets/admin/js/scripts.js');

mix.js('resources/js/app.js', 'public/js').postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
    require('autoprefixer'),
]);

mix.copyDirectory('resources/admin/webfonts', 'public/assets/admin/webfonts');
