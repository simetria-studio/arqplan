const mix = require('laravel-mix');
require('laravel-mix-transpile-node-modules')

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

mix
    .transpileNodeModules('gantt-elastic')
    .autoload({
        'jquery': ['$', 'window.jQuery', 'jQuery', 'jquery-sortable'],
        'vue': ['Vue'],   
        'moment': ['moment','window.moment'],   
    })
    .postCss('resources/css/tailwindcss.css', 'public/css', [require('tailwindcss')])
    .js('resources/js/app.js', 'public/js')
    .copy(['./node_modules/jquery/dist/jquery.js'], 'public/js/vendor.js')
    .copy('resources/images', 'public/images')
    .sass('resources/sass/app.scss', 'public/css').version().vue();
