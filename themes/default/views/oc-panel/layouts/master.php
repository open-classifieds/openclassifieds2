<?php defined('SYSPATH') or die('No direct script access.');?>

<!doctype html>
<head>
    <meta charset="<?= Kohana::$charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/png" sizes="16x16" href="<?= Theme::get('favicon_url') != '' ? Theme::get('favicon_url') : Core::config('general.base_url') . 'images/favicon.ico' ?>">
    <? if (Theme::get('apple-touch-icon') != NULL): ?>
        <link rel="icon" type="image/png" sizes="32x32" href="<?= Theme::get('apple-touch-icon') ?>">
    <? endif ?>

    <title><?=$title?></title>
    <meta name="keywords" content="<?= $meta_keywords ?>" >
    <meta name="description" content="<?= $meta_description ?>" >
    <meta name="application-name" content="<?= Core::config('general.site_name') ?>" data-baseurl="<?= Core::config('general.base_url') ?>">

    <?if (Core::extra_features() == FALSE):?>
        <meta name="author" content="open-classifieds.com">
        <meta name="copyright" content="<?= Core::config('general.site_name') ?>">
    <?else:?>
        <meta name="copyright" content="<?= $meta_copyright ?>">
    <?endif?>

    <!--  Disallow Bots -->
    <meta name="robots" content="noindex, nofollow, noodp, noydir">
    <meta name="googlebot" content="noindex, noarchive, nofollow, noodp">
    <meta name="slurp" content="noindex, nofollow, noodp">
    <meta name="bingbot" content="noindex, nofollow, noodp, noydir">
    <meta name="msnbot" content="noindex, nofollow, noodp, noydir">

    <? $styles = array_merge(['css/oc-panel/panel.css?v=' . Core::VERSION => 'screen'], $styles) ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.0.1/dist/alpine.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/jquery-bootgrid@1.3.1/dist/jquery.bootgrid.min.js"></script>
    <script src="/themes/default/js/iconPicker.min.js"></script>

    <!-- include iconPicker css/js -->
    <link href="/themes/default/css/fontawesome-iconpicker.min.css" rel="stylesheet">
    <script src="/themes/default/js/oc-panel/fontawesome-iconpicker.min.js"></script>

    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <!-- include sweetalert css/js -->
    <link href="/themes/default/css/sweet-alert.min.css" rel="stylesheet">
    <script src="/themes/default/js/sweet-alert.min.js"></script>

    <script src="/themes/default/js/oc-panel/theme.init.js?v=<?= Core::VERSION ?>"></script>

    <?= Theme::styles($styles, 'default') ?>
    <?= Theme::scripts($scripts, 'header','default') ?>

    <?if (Core::is_cloud() AND Auth::instance()->logged_in()):?>
        <script src="//<?=Model_Domain::get_sub_domain()?>/jslocalization/cloud_notifications"></script>
    <?elseif (Core::is_selfhosted() AND Auth::instance()->logged_in()):?>
        <script src="//yclas.com/jslocalization/selfhosted_notifications"></script>
    <?endif?>

    <style>
        .note-modal-backdrop {
            position: initial;
        }
    </style>
</head>
<body class="antialiased">
    <div class="h-screen flex overflow-hidden bg-gray-100" x-data="{ sidebarOpen: false }" @keydown.window.escape="sidebarOpen = false">
        <?= View::factory('oc-panel/layouts/_sidebar', ['user' => $user]) ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <div class="md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 flex items-center justify-between">
                <button @click.stop="sidebarOpen = true" class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:bg-gray-200 transition ease-in-out duration-150">
                    <svg class="mb-2 font-medium leading-tight text-base w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="p-4 -mt-4">
                    <?= View::factory('oc-panel/components/need-help') ?>
                </span>
            </div>
            <main class="flex-1 relative z-0 overflow-y-auto pt-2 pb-6 focus:outline-none md:py-6" tabindex="0" x-data x-init="$el.focus()">
                <div class="hidden md:block absolute right-0 top-0 mx-4 sm:mx-6 md:mx-8">
                    <?= View::factory('oc-panel/components/need-help') ?>
                </div>
                <? if($panel_title) : ?>
                    <div class="mx-auto px-4 sm:px-6 md:px-8">
                        <h1 class="text-2xl font-semibold text-gray-900"><?= $panel_title ?></h1>
                    </div>
                <? endif ?>
                <div class="mx-auto px-4 sm:px-6 md:px-8">
                    <?= Alert::show() ?>
                    <?= $content ?>
                    <? Kohana::$environment === Kohana::DEVELOPMENT ? View::factory('profiler') : '' ?>
                </div>
            </main>
        </div>
    </div>

    <?= Theme::scripts($scripts,'footer','default') ?>
    <?= Theme::scripts($scripts,'async_defer', 'default', ['async' => '', 'defer' => '']) ?>
</body>
</html>
