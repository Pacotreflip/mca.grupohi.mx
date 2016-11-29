var elixir = require('laravel-elixir');

var paths = {
    'jquery': './node_modules/jquery/dist/',
    'bootstrap': './node_modules/bootstrap-sass/assets/',
    'fontawesome': './node_modules/font-awesome/',
    'roboto': './node_modules/roboto-fontface/',
    'sweetalert': './node_modules/sweetalert/dist/',
    'fileinput': './node_modules/bootstrap-fileinput/'
};
    
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss')
            .copy(paths.roboto + 'fonts', 'public/build/fonts/roboto')
            .copy(paths.bootstrap + 'fonts/bootstrap', 'public/build/fonts/bootstrap')
            .copy(paths.fontawesome + 'fonts', 'public/build/fonts/font-awesome')
            .copy(paths.fileinput + 'img', 'public/build/img')
            .copy(paths.bootstrap + 'javascripts/bootstrap.js', './resources/assets/js')
            .copy(paths.jquery + 'jquery.js', './resources/assets/js')
            .copy(paths.fileinput + 'js/fileinput.js', './resources/assets/js')
            .copy(paths.fileinput + 'js/locales/es.js', './resources/assets/js/fileinput-es.js')
            .copy(paths.sweetalert + 'sweetalert-dev.js', './resources/assets/js')
            .scripts([
                'jquery.js',
                'bootstrap.js',
                'fileinput.js',
                'fileinput-es.js',
                'sweetalert.min.js',
                'app/*.js'
            ])
            .version([
                'public/css/app.css',
                'public/js/all.js'
            ]);
});
