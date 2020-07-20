let mix = require('laravel-mix');
const rootPath = Mix.paths.root.bind(Mix.paths);

require('./laravel-mix-tailwind.js');
require('laravel-mix-purgecss');

mix.setResourceRoot('../')
    .js('assets/js/theme.js', 'js')
    .postCss('assets/css/tailwind.css', 'css')
    .tailwind('./tailwind.config.js')
    .sass('assets/sass/bootstrap.scss', 'css')
    .styles([
        'css/tailwind.css',
        'css/bootstrap.css',
    ], 'css/theme.css')
    .purgeCss({
        extend: {
            content: [
                rootPath('views/**/*.php')
            ],
            whitelistPatterns: [/select2/],
        },
    });
