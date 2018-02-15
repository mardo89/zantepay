let mix = require('laravel-mix');

mix.webpackConfig({
    watchOptions: {
        aggregateTimeout: 2000,
        poll: 2000,
        ignored: /node_modules/
    }
});

mix.js('resources/assets/js/main.js', 'public/js')
    .js('resources/assets/js/user.js', 'public/js')
    .js('resources/assets/js/user_profile.js', 'public/js')
    .js('resources/assets/js/user_profile_settings.js', 'public/js')
    .js('resources/assets/js/user_refer_friend.js', 'public/js')
    .js('resources/assets/js/user_wallet.js', 'public/js')
    .js('resources/assets/js/user_debit_card.js', 'public/js')
    .js('resources/assets/js/admin.js', 'public/js')
    .js('resources/assets/js/admin_users.js', 'public/js')
    .js('resources/assets/js/admin_profile.js', 'public/js')
    .js('resources/assets/js/admin_wallet.js', 'public/js')
    .stylus('resources/assets/stylus/main.styl', 'public/css');
   // .sass('resources/assets/sass/app.scss', 'public/css');
