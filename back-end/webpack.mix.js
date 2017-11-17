let mix = require('laravel-mix');

mix.js('resources/assets/js/main.js', 'public/js')
    .stylus('resources/assets/stylus/main.styl', 'public/css');
   // .sass('resources/assets/sass/app.scss', 'public/css');
