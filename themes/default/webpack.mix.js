const mix = require("laravel-mix");

require("laravel-mix-tailwind");

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

mix.setPublicPath('./')
    .setResourceRoot('../')
    .js("assets/js/panel.js", "js/oc-panel/panel.js")
    .sass("assets/sass/panel.scss", "css/oc-panel/panel.css")
    .tailwind("./tailwind.config.js")
    // .copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'css/webfonts')
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}
