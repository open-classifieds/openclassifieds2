<?php defined('SYSPATH') or die('No direct script access.');?>

<nav class="mt-5 px-2">
    <?= Theme::admin_sidebar_link(__('Dashboard'), 'home', 'index', '<svg stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10M9 21h6"/></svg>') ?>
</nav>
<nav class="mt-5 flex-1 px-2">
    <h3 class="px-2 text-xs leading-4 font-semibold text-blue-500 uppercase tracking-wider">
        <?= __('Manage') ?>
    </h3>
    <?= Theme::admin_sidebar_link(__('Advertisements'), 'ad', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>') ?>
    <? if (in_array(core::config('general.moderation'), Model_Ad::$moderation_status)): ?>
        <?= Theme::admin_sidebar_link(__('Moderation'), 'ad', 'moderate', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>') ?>
    <? endif ?>
    <?= Theme::admin_sidebar_link(__('Users'), 'user', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>') ?>
    <? if(Core::config('general.subscriptions')) : ?>
        <?= Theme::admin_sidebar_link(__('Subscriptions'), 'subscription', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>') ?>
    <? endif ?>
    <?= Theme::admin_sidebar_link(__('Subscribers'), 'subscriber', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>') ?>
    <?= Theme::admin_sidebar_link(__('Orders'), 'order', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>') ?>
    <? if(Core::config('advertisements.reviews')) : ?>
        <?= Theme::admin_sidebar_link(__('Reviews'), 'review', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>') ?>
    <? endif ?>
    <? if(Core::config('general.forums')) : ?>
        <?= Theme::admin_sidebar_link(__('Forum'), 'topic', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>') ?>
    <? endif ?>
    <? Theme::admin_sidebar_link(__('Metrics'), 'stats', 'index', '') ?>
</nav>
<nav class="mt-5 px-2">
    <h3 class="px-2 text-xs leading-4 font-semibold text-blue-500 uppercase tracking-wider">
        <?= __('Content') ?>
    </h3>
    <?= Theme::admin_sidebar_link(__('Pages'), 'pages', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>') ?>
    <? if(Core::config('general.blog')) : ?>
        <?= Theme::admin_sidebar_link(__('Blog'), 'blog', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>') ?>
    <? endif ?>
    <? if(Core::config('general.faq')) : ?>
        <?= Theme::admin_sidebar_link(__('FAQ'), 'faqs', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>') ?>
    <? endif ?>
</nav>
<nav class="mt-5 px-2">
    <h3 class="px-2 text-xs leading-4 font-semibold text-blue-500 uppercase tracking-wider">
        <?= __('Configure') ?>
    </h3>
    <?= Theme::admin_sidebar_link(__('Settings'), 'settings', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>') ?>
    <?= Theme::admin_sidebar_link(__('Design'), 'design', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>') ?>
    <?= Theme::admin_sidebar_link(__('Addons'), 'addons', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>') ?>
    <?= Theme::admin_sidebar_link(__('Integrations'), 'integrations', 'index', '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>') ?>
</nav>

<nav class="mt-5 px-2">
    <?= Theme::admin_sidebar_link(__('Tools'), 'tools', 'index', '<svg fill="none" viewBox="0 0 512 512" stroke="currentColor"><path fill="currentColor" d="M507.48 117.18c-3-12.17-12.41-21.79-24.5-25.15-12.1-3.34-25.16.11-33.97 8.97l-58.66 58.63-32.44-5.4-5.38-32.41 58.67-58.64c8.84-8.89 12.28-21.92 8.91-33.99-3.38-12.11-13.06-21.5-25.29-24.53-53.09-13.19-107.91 2.07-146.54 40.69-37.63 37.62-52.6 91.37-40.72 143.27L24.04 372.06C8.53 387.53 0 408.12 0 430.02s8.53 42.49 24.04 57.97C39.51 503.47 60.1 512 82.01 512c21.88 0 42.47-8.53 57.98-24.01l183.34-183.26c51.79 11.87 105.64-3.14 143.49-40.93 38.09-38.1 53.69-94.27 40.66-146.62zm-74.61 112.69c-28.47 28.46-70.2 38.1-109.01 25.21l-14.06-4.69-203.75 203.67c-12.85 12.84-35.29 12.81-48.07 0-6.44-6.42-9.97-14.96-9.97-24.04 0-9.08 3.53-17.61 9.97-24.03l203.84-203.78-4.63-14.03c-12.81-38.9-3.22-80.62 25.04-108.9 20.35-20.32 47.19-31.24 75.04-31.24h1.12l-57.32 57.3 15.13 90.59 90.57 15.09 57.35-57.29c.32 28.26-10.62 55.52-31.25 76.14zM88.01 408.02c-8.84 0-16 7.16-16 16s7.16 16 16 16 16-7.16 16-16-7.16-16-16-16z" class=""></path></svg>') ?>
</nav>
