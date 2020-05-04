<?php defined('SYSPATH') or die('No direct script access.');?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?=i18n::html_lang()?>"> <!--<![endif]-->
<head>
	<meta charset="<?=Kohana::$charset?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=$title?></title>
    <meta name="keywords" content="<?=$meta_keywords?>" >
    <meta name="description" content="<?=$meta_description?>" >
    
    <?if (Core::extra_features() == FALSE):?>
    <meta name="author" content="open-classifieds.com">
    <meta name="copyright" content="<?=Core::config('general.site_name')?>" >
    <?else:?>
    <meta name="copyright" content="<?=$meta_copyright?>" >
    <?endif?>
    <meta name="application-name" content="<?=core::config('general.site_name')?>" data-baseurl="<?=core::config('general.base_url')?>">
	
	<meta name="viewport" content="width=device-width,initial-scale=1">
	
	<!--  Disallow Bots -->
	<meta name="robots" content="noindex,nofollow,noodp,noydir">
	<meta name="googlebot" content="noindex,noarchive,nofollow,noodp">
	<meta name="slurp" content="noindex,nofollow,noodp">
	<meta name="bingbot" content="noindex,nofollow,noodp,noydir">
	<meta name="msnbot" content="noindex,nofollow,noodp,noydir">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
    <![endif]-->
    
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,400italic,500,700' rel='stylesheet' type='text/css'>
    
    <?=Theme::styles($styles,'default')?>	
	<?=Theme::scripts($scripts,'header','default')?>
    <link rel="shortcut icon" href="<?=core::config('general.base_url').'images/favicon.ico'?>">
    
<?if (Auth::instance()->logged_in()):?>
<script src="//yclas.com/jslocalization/selfhosted_notifications"></script>
<?endif?>

  </head>

  <body>
    <div id="wrapper">
      <?=$header?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-xs-12">
            <?=View::factory('oc-panel/sidebar',array('user'=>$user))?>
            <div id="page-wrapper">
              <div class="row">
                <div class="col-xs-12">
                  <?=Breadcrumbs::render('oc-panel/breadcrumbs')?>
                  <?=Alert::show()?>
                  <?=$content?>
                  <?=(Kohana::$environment === Kohana::DEVELOPMENT)? View::factory('profiler'):''?>
                </div>
              </div>
            </div>
            <?=$footer?>
          </div>
        </div>
      </div>  
    </div>
    <?=Theme::scripts($scripts,'footer','default')?>
    <?=Theme::scripts($scripts,'async_defer', 'default', ['async' => '', 'defer' => ''])?>
  </body>
</html>

