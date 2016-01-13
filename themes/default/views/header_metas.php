<meta charset="<?=Kohana::$charset?>">
<?if (isset($_SERVER['HTTP_USER_AGENT']) and (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) : ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<?endif?>

<title><?=$title?></title>
<meta name="keywords" content="<?=$meta_keywords?>" >
<meta name="description" content="<?=HTML::chars($meta_description)?>" >
<meta name="copyright" content="<?=HTML::chars($meta_copyright)?>" >
<?if (Theme::get('premium')==1):?>
<meta name="viewport" content="width=device-width,initial-scale=1">
<?else:?>
<meta name="author" content="open-classifieds.com">
<?endif?>
<meta name="application-name" content="<?=core::config('general.site_name')?>" data-baseurl="<?=core::config('general.base_url')?>">
<?if (core::config('general.landing_page')!=NULL 
    AND strtolower(Request::current()->controller())=='ad' 
    AND strtolower(Request::current()->action())=='listing' 
    AND Request::current()->param('category') == URL::title(__('all'))):?>
<link rel="canonical" href="<?=Route::url('default')?>" />
<?endif?>
<?if (Controller::$image!==NULL):?>
<meta property="og:image"   content="<?=Controller::$image?>"/>
<?elseif(Theme::get('logo_url')!=NULL):?>
<meta property="og:image"   content="<?=Theme::get('logo_url')?>"/>
<?endif?>
<meta property="og:title"   content="<?=HTML::chars($title)?>"/>
<meta property="og:description"   content="<?=HTML::chars($meta_description)?>"/>
<meta property="og:url"     content="<?=URL::current()?>"/>
<meta property="og:site_name" content="<?=HTML::chars(core::config('general.site_name'))?>"/>

<?if (core::config('general.disallowbots')=='1'):?>
    <meta name="robots" content="noindex,nofollow,noodp,noydir" />
    <meta name="googlebot" content="noindex,noarchive,nofollow,noodp" />
    <meta name="slurp" content="noindex,nofollow,noodp" />
    <meta name="bingbot" content="noindex,nofollow,noodp,noydir" />
    <meta name="msnbot" content="noindex,nofollow,noodp,noydir" />
<?endif?>

<?if (core::config('general.blog')==1):?>
<link rel="alternate" type="application/atom+xml" title="RSS Blog <?=HTML::chars(Core::config('general.site_name'))?>" href="<?=Route::url('rss-blog')?>" />
<?endif?>
<?if (core::config('general.forums')==1):?>
<link rel="alternate" type="application/atom+xml" title="RSS Forum <?=HTML::chars(Core::config('general.site_name'))?>" href="<?=Route::url('rss-forum')?>" />
  <?if (Model_Forum::current()->loaded()):?>
  <link rel="alternate" type="application/atom+xml" title="RSS Forum <?=HTML::chars(Core::config('general.site_name'))?> - <?=Model_Forum::current()->name?>" href="<?=Route::url('rss-forum', array('forum'=>Model_Forum::current()->seoname))?>" />
  <?endif?>
<?endif?>
<?if (Model_User::current()!=NULL AND Model_User::current()->loaded()):?>
  <link rel="alternate" type="application/atom+xml" title="RSS Profile - <?=HTML::chars(Model_User::current()->name)?>" href="<?=Route::url('rss-profile', array('seoname'=>Model_User::current()->seoname))?>" />
  <?endif?>
<link rel="alternate" type="application/atom+xml" title="RSS <?=HTML::chars(Core::config('general.site_name'))?>" href="<?=Route::url('rss')?>" />


<?if (Model_Category::current()->loaded() AND Model_Location::current()->loaded()):?>
<link rel="alternate" type="application/atom+xml"  title="RSS <?=HTML::chars(Core::config('general.site_name').' - '.Model_Category::current()->name)?> - <?=Model_Location::current()->name?>"  href="<?=Route::url('rss',array('category'=>Model_Category::current()->seoname,'location'=>Model_Location::current()->seoname))?>" />
<?elseif (Model_Location::current()->loaded()):?>
<link rel="alternate" type="application/atom+xml"  title="RSS <?=HTML::chars(Core::config('general.site_name').' - '.Model_Location::current()->name)?>"  href="<?=Route::url('rss',array('category'=>URL::title(__('all')),'location'=>Model_Location::current()->seoname))?>" />
<?elseif (Model_Category::current()->loaded()):?>
<link rel="alternate" type="application/atom+xml"  title="RSS <?=HTML::chars(Core::config('general.site_name').' - '.Model_Category::current()->name)?>"  href="<?=Route::url('rss',array('category'=>Model_Category::current()->seoname))?>" />
<?endif?>    

<?if (core::config('advertisement.logbee')==1 AND Model_Ad::current()!==NULL AND Model_Ad::current()->loaded()):?>
<meta property="logbee:type" content="various"/>
<meta property="logbee:title" content="<?=Model_Ad::current()->title?>"/>
<meta property="logbee:url" content="<?=URL::current()?>"/>
<meta property="logbee:desc" content="<?=Model_Ad::current()->description?>"/>
<meta property="logbee:addr" content="<?=Model_Ad::current()->address?>"/>
<meta property="logbee:email" content="<?=Model_Ad::current()->user->email?>"/>
<meta property="logbee:phone" content="<?=Model_Ad::current()->phone?>"/>
<meta property="logbee:price" content="<?=i18n::money_format(Model_Ad::current()->price)?>"/>
<meta property="logbee:imgurl" content="<?=Controller::$image?>"/>
<?endif?> 

<link rel="shortcut icon" href="<?=(Theme::get('favicon_url')!='') ? Theme::get('favicon_url') : core::config('general.base_url').'images/favicon.ico'?>">