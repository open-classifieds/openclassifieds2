<meta charset="<?=Kohana::$charset?>">

<title><?= $title ?></title>
<meta name="keywords" content="<?= $meta_keywords ?>" >
<meta name="description" content="<?= HTML::chars($meta_description) ?>">
<meta name="copyright" content="<?= HTML::chars($meta_copyright) ?>">

<? if (isset($_SERVER['SERVER_ADDR'])): ?>
    <meta name="server_addr" content="<?= $_SERVER['SERVER_ADDR'] ?>">
<? endif ?>

<? if (Core::extra_features() == TRUE): ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<? else: ?>
    <meta name="author" content="yclas.com">
<?endif?>

<meta name="application-name" content="<?=core::config('general.site_name')?>" data-baseurl="<?=core::config('general.base_url')?>" data-rtl="<?=Theme::get('rtl') ? TRUE : FALSE?>">
<? if (core::config('general.landing_page') != NULL
    AND strtolower(Request::current()->controller()) == 'ad'
    AND strtolower(Request::current()->action()) == 'listing'
    AND Request::current()->param('category') == URL::title(__('all'))):?>
    <link rel="canonical" href="<?= Route::url('default') ?>">
<? endif ?>

<meta property="fb:app_id" content="<?= core::config('advertisement.facebook_app_id') ?>">
<meta property="fb:pages" content="<?= core::config('advertisement.facebook_id') ?>">

<? if (Controller::$image!==NULL): ?>
    <meta property="og:image" content="<?=Controller::$image?>">
<? elseif(Theme::get('logo_url') != NULL): ?>
    <meta property="og:image" content="<?=Theme::get('logo_url')?>">
<? endif ?>

<meta property="og:title" content="<?= HTML::chars($title) ?>">
<meta property="og:type" content="website">
<meta property="og:description" content="<?= HTML::chars($meta_description) ?>">
<meta property="og:url" content="<?= URL::current() ?>">
<meta property="og:site_name" content="<?= HTML::chars(core::config('general.site_name')) ?>">

<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="<?= HTML::chars(core::config('general.site_name')) ?>">
<meta name="twitter:title" content="<?= HTML::chars($title) ?>">
<meta name="twitter:description" content="<?= HTML::chars($meta_description) ?>">

<?if (Controller::$image !== NULL):?>
    <meta name="twitter:image" content="<?= Controller::$image ?>">
<?elseif(Theme::get('logo_url') != NULL):?>
    <meta name="twitter:image" content="<?= Theme::get('logo_url') ?>">
<?endif?>

<meta name="twitter:image:alt" content="<?= HTML::chars($title) ?>">

<? if(core::config('general.disallowbots')=='1'): ?>
    <meta name="robots" content="noindex,nofollow,noodp,noydir">
    <meta name="googlebot" content="noindex,noarchive,nofollow,noodp">
    <meta name="slurp" content="noindex,nofollow,noodp">
    <meta name="bingbot" content="noindex,nofollow,noodp,noydir">
    <meta name="msnbot" content="noindex,nofollow,noodp,noydir">
<? endif ?>

<? if (core::config('general.blog') == 1): ?>
    <link rel="alternate" type="application/atom+xml" title="RSS Blog <?= HTML::chars(Core::config('general.site_name')) ?>" href="<?= Route::url('rss-blog') ?>">
<? endif ?>

<? if (core::config('general.forums') == 1): ?>
    <link rel="alternate" type="application/atom+xml" title="RSS Forum <?= HTML::chars(Core::config('general.site_name')) ?>" href="<?= Route::url('rss-forum') ?>">

    <? if (Model_Forum::current()->loaded()): ?>
        <link rel="alternate" type="application/atom+xml" title="RSS Forum <?=HTML::chars(Core::config('general.site_name'))?> - <?=Model_Forum::current()->name?>" href="<?=Route::url('rss-forum', array('forum'=>Model_Forum::current()->seoname))?>">
    <? endif ?>
<?endif?>

<?if (Model_User::current() != NULL AND Model_User::current()->loaded()):?>
    <link rel="alternate" type="application/atom+xml" title="RSS Profile - <?=HTML::chars(Model_User::current()->name)?>" href="<?=Route::url('rss-profile', array('seoname'=>Model_User::current()->seoname))?>">
<?endif?>

<link rel="alternate" type="application/atom+xml" title="RSS <?=HTML::chars(Core::config('general.site_name'))?>" href="<?=Route::url('rss')?>">

<?if (Model_Category::current()->loaded() AND Model_Location::current()->loaded()):?>
    <link rel="alternate" type="application/atom+xml"  title="RSS <?=HTML::chars(Core::config('general.site_name').' - '.Model_Category::current()->name)?> - <?=Model_Location::current()->name?>"  href="<?=Route::url('rss',array('category'=>Model_Category::current()->seoname,'location'=>Model_Location::current()->seoname))?>">
<?elseif (Model_Location::current()->loaded()):?>
    <link rel="alternate" type="application/atom+xml"  title="RSS <?=HTML::chars(Core::config('general.site_name').' - '.Model_Location::current()->name)?>"  href="<?=Route::url('rss',array('category'=>URL::title(__('all')),'location'=>Model_Location::current()->seoname))?>">
<?elseif (Model_Category::current()->loaded()):?>
    <link rel="alternate" type="application/atom+xml"  title="RSS <?=HTML::chars(Core::config('general.site_name').' - '.Model_Category::current()->name)?>"  href="<?=Route::url('rss',array('category'=>Model_Category::current()->seoname))?>">
<?endif?>

<?if (core::config('advertisement.logbee')==1 AND Model_Ad::current()!==NULL AND Model_Ad::current()->loaded()):?>
    <meta property="logbee:type" content="various"/>
    <meta property="logbee:title" content="<?=Model_Ad::current()->title?>"/>
    <meta property="logbee:url" content="<?=URL::current()?>"/>
    <meta property="logbee:desc" content="<?=Model_Ad::current()->description?>"/>
    <meta property="logbee:addr" content="<?=Model_Ad::current()->address?>"/>
    <meta property="logbee:email" content="<?=Model_Ad::current()->user->email?>"/>
    <meta property="logbee:phone" content="<?=Model_Ad::current()->phone?>"/>
    <meta property="logbee:price" content="<?=i18n::money_format(Model_Ad::current()->price, Model_Ad::current()->currency())?>"/>
    <meta property="logbee:imgurl" content="<?=Controller::$image?>"/>
<?endif?>

<link rel="shortcut icon" href="<?=(Theme::get('favicon_url')!='') ? Theme::get('favicon_url') : core::config('general.base_url').'images/favicon.ico'?>">

<?if(Theme::get('apple-touch-icon')!=NULL):?>
    <link rel="apple-touch-icon" href="<?=(Theme::get('apple-touch-icon')) ?>">
<?endif?>

<?if(Theme::get('theme_color')!=NULL):?>
    <meta name="theme-color" content="<?=Theme::get('theme_color')?>">
<?endif?>

<?if (isset($amphtml) AND $amphtml):?>
    <link rel="amphtml" href="<?=$amphtml?>">
<?endif?>

<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v1.9.7/dist/alpine.js" defer></script>
