const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = 'resources'
const dist = '../../../public/vendor/core/plugins/hall-of-fame'

mix
    .setPublicPath(dist)
    .js(`${source}/js/fb-pixel-fix.js`, 'js')
    .js(`${source}/js/hall-of-fame.js`, 'js')
    .sass(`${source}/sass/hall-of-fame.scss`, 'css')
    .version()

if (mix.inProduction()) {
    mix
        .copy(`${dist}/js/fb-pixel-fix.js`, 'public/js')
        .copy(`${dist}/js/hall-of-fame.js`, 'public/js')
        .copy(`${dist}/css/hall-of-fame.css`, 'public/css')
}
