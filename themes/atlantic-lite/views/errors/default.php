<?php defined('SYSPATH') or die('No direct script access.');?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Yclas">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">

    <title>An error :'(</title>

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
        
        <?if (Auth::instance()->get_user()):?>
        <?if (Auth::instance()->get_user()->is_admin()):?>
            <p>Since you are logged in as admin only you can see this message:</p>
            <code><?=$message?></code>
        <p>It's been logged in <a href="<?php echo Route::url('oc-panel',array('controller'=>'tools','action'=>'logs')) ?>">Panel->Extra->Tools->Logs</a> for more information regarding this error.</p>
        <?endif?>
        <?endif?>

        <h1>Sorry!</h1>
        <p>Something went wrong with your request. 

        This incident is logged and we are already notified about this problem.</p>

        <p>You can go <a href="javascript: history.go(-1)">Back</a> or to our <a href="<?php echo URL::site('/', TRUE) ?>">Home page</a>.</p>
      </div>


    </div> 

  </body>
</html>
