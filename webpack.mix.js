const mix = require('laravel-mix')

mix
    .setPublicPath('public')
    .js('resources/js/hall-of-fame.js', 'js')
    .sass('resources/sass/hall-of-fame.scss', 'css')
    .copyDirectory('public', '../../../public/vendor/core/plugins/hall-of-fame')
