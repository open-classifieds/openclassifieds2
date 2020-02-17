let mix = require('laravel-mix');

const tailwindcss = require('tailwindcss')

mix
    .setResourceRoot('../')
    .js('assets/js/theme.js', 'js')
    .postCss('assets/css/tailwind.css', 'css', [
        tailwindcss('tailwind.config.js'),
    ])
    .sass('assets/sass/bootstrap.scss', 'css')
    .styles([
        'css/tailwind.css',
        'css/bootstrap.css',
    ], 'css/theme.css');
