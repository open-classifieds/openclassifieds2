<?php defined('SYSPATH') or die('No direct script access.');?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="We are working on our site, please visit later. Thanks">
    <meta name="author" content="Yclas">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <title><?=Core::config('general.site_name')?> - <?=__('Maintenance')?></title>
    <link href="//cdn.jsdelivr.net/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <style type="text/css">
    /* Space out content a bit */
    body {
      padding-top: 20px;
      padding-bottom: 20px;
	  background: #eee;
    }
	
	.maintenance-mode {
		border: 1px solid #DDD;
    padding: 100px 0px;
    text-align: center;
    background: #FFF;
	}
	
	.maintenance-mode h1 {
		margin: 0px 5px 10px 5px;
    color: #7D7D7D;
	}
	.maintenance-mode p {
	    padding: 10px;	
	}

    /* Customize container */
    @media (min-width: 768px) {
      .container {
        max-width: 730px;
      }
    }

    </style>

<div class="container">
	<div class="col-xs-12">
		<div class="maintenance-mode">
			<h1><?=Core::config('general.site_name')?></h1>
			<p><?=__('We are working on our site, please visit later. Thanks')?></p>
			<div class="text-center">
				<a class="btn btn-success btn-sm" title="<?=__('Login')?>" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>">
					<i class="glyphicon glyphicon-user"></i> 
					<?=__('Login')?>
				</a>    
			</div>
		</div>
	</div> 
</div>

</body>

</html>
