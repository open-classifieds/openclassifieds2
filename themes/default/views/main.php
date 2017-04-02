<?php defined('SYSPATH') or die('No direct script access.');?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="<?=i18n::html_lang()?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?=i18n::html_lang()?>"> <!--<![endif]-->
<head>
<?=View::factory('header_metas',array('title'             => $title,
                                      'meta_keywords'     => $meta_keywords,
                                      'meta_description'  => $meta_description,
                                      'meta_copyright'    => $meta_copyright,
                                      'amphtml'           => $amphtml,))?> 
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 7]><link rel="stylesheet" href="https://raw.githubusercontent.com/ddouble/bsie/master/bootstrap/css/bootstrap-ie6.min.csss"><![endif]-->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
    <![endif]-->
    <?=Theme::styles($styles)?> 
    <?=Theme::scripts($scripts)?>
    <?=core::config('general.html_head')?>
    <?=View::factory('analytics')?>
</head>

  <body data-spy="scroll" data-target=".subnav" data-offset="50">

    <?=View::factory('alert_terms')?>

    <?=$header?>
    <div id="content" class="container">
        <div class="alert alert-warning off-line" style="display:none;"><strong><?=_e('Warning')?>!</strong> <?=_e('We detected you are currently off-line, please connect to gain full experience.')?></div>
        <div class="row">
            <?if(Controller::$full_width):?>
                <div class="col-xs-12">
                    <?=Breadcrumbs::render('breadcrumbs')?>
                    <?=Alert::show()?>
                    <div id="main-content">
                      <?=$content?>
                    </div>
                </div>
            <?else:?>
                <div class="col-xs-9">
                    <?=Breadcrumbs::render('breadcrumbs')?>
                    <?=Alert::show()?>
                    <div id="main-content">
                      <?=$content?>
                    </div>
                </div>
                <?if(Core::config('general.algolia_search') == 1):?>
                  <div class="col-xs-3">
                    <div class="form-group">
                      <?=View::factory('pages/algolia/autocomplete')?>
                    </div>
                  </div>
                <?else:?>
                  <?= FORM::open(Route::url('search'), array('class'=>'col-xs-3', 'method'=>'GET', 'action'=>''))?>
                      <div class="form-group">
                          <input type="text" name="title" class="search-query form-control" placeholder="<?=__('Search')?>">
                      </div>
                  <?= FORM::close()?>
                <?endif?>
                <?=View::fragment('sidebar_front','sidebar')?>
            <?endif?>
        </div>
        <?=$footer?>
    </div>

  <?=Theme::scripts($scripts,'footer')?>
  <?=Theme::scripts($scripts,'async_defer', 'default', ['async' => '', 'defer' => ''])?>
  <?=core::config('general.html_footer')?>

  <?=(Kohana::$environment === Kohana::DEVELOPMENT)? View::factory('profiler'):''?>
  </body>
</html>
