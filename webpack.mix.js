let mix = require('laravel-mix')
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

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/scss/main.scss', 'public/css')
    .copyDirectory('resources/assets/img', 'public/img')
    .copy('resources/assets/apple-touch-icon.png', 'public/apple-touch-icon.png')
    .copy('resources/assets/browserconfig.xml', 'public/browserconfig.xml')
    .copy('resources/assets/favicon.ico', 'public/favicon.ico')
    .babel([
        'resources/assets/js/theme/vendor/jquery.min.js',
        'resources/assets/js/theme/vendor/bootstrap.bundle.min.js',
        'resources/assets/js/theme/vendor/select2.full.min.js',
        'resources/assets/js/theme/vendor/dropzone.min.js',
        'resources/assets/js/theme/vendor/datepicker.min.js',
        'resources/assets/js/theme/vendor/jquery.timepicker.js',
        'resources/assets/js/theme/sidebar.js',
        'resources/assets/js/theme/main.js',
    ], 'public/js/main.js')
    .browserSync({
        proxy: process.env.MIX_DEV_URL,
    })

if (mix.inProduction()) {
    mix.version()
}
