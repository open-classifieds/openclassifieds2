const mix = require("laravel-mix");
const rootPath = Mix.paths.root.bind(Mix.paths);

require('./laravel-mix-tailwind.js');

mix.setPublicPath('./')
    .setResourceRoot('../')
    .js('assets/js/theme.js', 'js')
    .postCss('assets/css/tailwind.css', 'css')
    .tailwind('./tailwind.config.js')
    .sass('assets/sass/bootstrap.scss', 'css')
    .styles([
        'css/tailwind.css',
        'css/bootstrap.css',
    ], 'css/theme.css');
