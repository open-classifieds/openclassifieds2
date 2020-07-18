<?php defined('SYSPATH') or die('No direct script access.');?>

<!DOCTYPE html>
<html lang="<?=i18n::html_lang()?>">
  <head>
    <meta charset="<?=Kohana::$charset?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?=__('This is a private website, you need to login to see the content!')?>">
    <meta name="author" content="Yclas">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">

    <title><?=Core::config('general.site_name')?> - <?=__('Private Site')?></title>

    <link href="//cdn.jsdelivr.net/npm/bootstrap@3.4.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/bootstrap.image-gallery/3.1.0/css/bootstrap-image-gallery.min.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/blueimp-gallery/2.14.0/css/blueimp-gallery.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/blueimp-gallery/2.14.0/js/jquery.blueimp-gallery.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.image-gallery/3.1.0/js/bootstrap-image-gallery.min.js"></script>

  </head>

  <body>
    <style type="text/css">
    /* Space out content a bit */
    body {
      padding-top: 20px;
      padding-bottom: 20px;
    }

    /* Customize container */
    @media (min-width: 768px) {
      .container {
        max-width: 730px;
      }
    }
    .container-narrow > hr {
      margin: 30px 0;
    }

    /* Main marketing message and sign up button */
    .jumbotron {
      text-align: center;
      border-bottom: 1px solid #e5e5e5;
    }
    .jumbotron .btn {
      padding: 14px 24px;
      font-size: 21px;
    }

    /* Responsive: Portrait tablets and up */
    @media screen and (min-width: 768px) {
      /* Remove the bottom border on the jumbotron for visual effect */
      .jumbotron {
        border-bottom: 0;
      }
    }
    </style>

    <div class="container">
        <?=Alert::show()?>
      <div class="jumbotron">
        <?if(core::config('general.private_site_page') != ''):?>
      <?$content = Model_Content::get_by_title(core::config('general.private_site_page'))?>
      <div class="page-header">
        <h1><?=$content->title?></h1>
      </div>
      <div class="text-description"><?=$content->description?></div>
      <br>
    <?else:?>
      <div class="page-header">
        <h1><?=Core::config('general.site_name')?></h1>
        <h2><?=__('This is a private website, you need to login to see the content!')?></h2>
      </div>
    <?endif?>

      </div>
      <form class="well form-horizontal auth" method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>">
        <?=Form::errors()?>
        <div class="form-group">
            <label class="col-sm-2 control-label"><?=__('Email')?></label>
            <div class="col-md-5 col-sm-6">
                <input class="form-control" type="text" name="email" placeholder="<?=__('Email')?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label"><?=__('Password')?></label>
            <div class="col-md-5 col-sm-6">
                <input class="form-control" type="password" name="password" placeholder="<?=__('Password')?>">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" checked="checked"><?=__('Remember me')?>
                    </label>
                </div>
            </div>
        </div>
        <div class="page-header"></div>
        <div class="col-sm-offset-2">
            <button type="submit" class="btn btn-primary">
                <i class="glyphicon glyphicon-user glyphicon"></i> <?=__('Login')?>
            </button>
            <a data-toggle="modal" data-dismiss="modal" class="btn btn-info" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'request'))?>#request-modal">
                <?=__('Request Access')?>
              </a>
        </div>
        <?=Form::CSRF('login')?>
    </form>


    </div>
    <div id="request-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal" >&times;</a>
                    <h3><?=__('Request Access')?></h3>
                </div>
                <div class="modal-body">
                    <?=View::factory('pages/auth/request-form')?>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>
