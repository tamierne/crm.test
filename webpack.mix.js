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
    'resources/admin/css/adminlte.css',
    'resources/admin/plugins/fontawesome-free/css/all.min.css'
    ], 'public/assets/admin/css/styles.css');

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);

mix.scripts([
    'resources/admin/plugins/jquery/jquery.min.js',
    'resources/admin/plugins/bootstrap/js/bootstrap.bundle.min.js',
    'resources/admin/js/adminlte.min.js',
], 'public/assets/admin/js/scripts.js');

mix.copyDirectory('resources/admin/plugins/fontawesome-free/webfonts', 'public/assets/admin/webfonts');

mix.copy('resources/admin/css/adminlte.css.map', 'public/assets/admin/css/adminlte.css.map');
mix.copy('resources/admin/js/adminlte.min.js.map', 'public/assets/admin/js/adminlte.min.js.map');
