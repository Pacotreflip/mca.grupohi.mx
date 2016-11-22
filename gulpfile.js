var elixir = require('laravel-elixir');

var paths = {
    'jquery': './node_modules/jquery/dist/',
    'bootstrap': './node_modules/bootstrap-sass/assets/',
    'fontawesome': './node_modules/font-awesome/'
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
   
    mix.sass('app.scss', 'public/css/')
            .copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts')
            .copy(paths.fontawesome + 'fonts/**', 'public/fonts')
            .copy(paths.bootstrap + 'javascripts/bootstrap.min.js', './resources/assets/js')
            .copy(paths.jquery + 'jquery.min.js', './resources/assets/js')
            .scripts([
                'jquery.min.js',
                'bootstrap.min.js'
            ])
                    .styles([
                
                    ])
            .version([
                'public/css/app.css',
                'public/js/all.js'
            ]);
});