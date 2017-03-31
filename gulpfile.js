var elixir = require('laravel-elixir');

var paths = {
    'bootstrap': './node_modules/bootstrap-sass/assets/',
    'fontawesome': './node_modules/font-awesome/',
    'roboto': './node_modules/roboto-fontface/',
    'fileinput': './node_modules/bootstrap-fileinput/',
    'treegrid': './node_modules/jquery-treegrid/',
    'tablefilter': './node_modules/tablefilter/'
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
   /* mix.sass('app.scss', './resources/assets/css/sass.css')
            .less('app.less', './resources/assets/css/less.css')
            .styles(['*.css'], 'public/css/app.css')
            .copy(paths.roboto + 'fonts', 'public/build/fonts/roboto')
            .copy(paths.bootstrap + 'fonts/bootstrap', 'public/build/fonts/bootstrap')
            .copy(paths.fontawesome + 'fonts', 'public/build/fonts/font-awesome')
            .copy(paths.fileinput + 'img', 'public/build/img')
            .copy(paths.treegrid + 'img', 'public/build/img')
            .copy(paths.tablefilter + 'dist/tablefilter', 'public/tablefilter')
     */
         mix.browserify('app.js')
            .version([
                'public/css/app.css',
                'public/js/app.js'
        ]);
});
