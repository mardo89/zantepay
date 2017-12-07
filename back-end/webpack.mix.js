let mix = require('laravel-mix');

mix.js('resources/assets/js/main.js', 'public/js')
    .js('resources/assets/js/user.js', 'public/js')
    .js('resources/assets/js/admin.js', 'public/js')
    .stylus('resources/assets/stylus/main.styl', 'public/css');
   // .sass('resources/assets/sass/app.scss', 'public/css');
