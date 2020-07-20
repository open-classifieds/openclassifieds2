<!DOCTYPE html>
<html lang="<?=i18n::html_lang()?>">
  <head>
    <meta charset="<?=Kohana::$charset?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?=__('We are working on our site, please visit later. Thanks')?>">
    <meta name="author" content="Yclas">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">

    <title><?=Core::config('general.site_name')?> - <?=__('Maintenance')?></title>

    <link href="//cdn.jsdelivr.net/npm/bootstrap@3.4.0/dist/css/bootstrap.min.css" rel="stylesheet">

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

      <div class="jumbotron">
        
        

        <h1><?=Core::config('general.site_name')?></h1>
        <h2><?=__('We are working on our site, please visit later. Thanks')?></h2>

        
      </div>
      <a class="btn btn-default btn-sm" title="<?=__('Login')?>" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>">
                <i class="glyphicon glyphicon-user"></i> 
                <?=__('Login')?>
            </a>    

    </div> 

  </body>
</html>
