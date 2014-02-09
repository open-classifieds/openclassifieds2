<?php  
/**
 * HTML for the install
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
error_reporting(0);
include 'functions.php';
include 'install.php';
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en>"> <!--<![endif]-->
<head>
    <meta charset="utf8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Open Classifieds <?php echo __("Installation")?></title>
    <meta name="keywords" content="" >
    <meta name="description" content="" >
    <meta name="copyright" content="Open Classifieds <?php echo VERSION?>" >
    <meta name="author" content="Open Classifieds">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="shortcut icon" href="http://open-classifieds.com/wp-content/uploads/2012/04/favicon1.ico" />


    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>    <![endif]-->
    
    <link type="text/css" href="" rel="stylesheet" media="screen" />    
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
      .we-install{margin-top: 8px;}
      #myTab{margin-bottom: 20px; margin-top: 20px;}
    </style>
        
    <link href="themes/default/css/bootstrap.min.css" rel="stylesheet">

  </head>

  <body>

    <!--phpinfo Modal -->
    <div id="phpinfoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                <?php 
                //getting the php info clean!
                ob_start();                                                                                                        
                @phpinfo();                                                                                                     
                $phpinfo = ob_get_contents();                                                                                         
                ob_end_clean();  
                //strip the body html                                                                                                  
                $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
                //adding our class
                echo str_replace('<table', '<table class="table table-striped  table-bordered"', $phpinfo);
                ?>
              </div>
            </div>
        </div>
    </div>
    <!--END phpinfo Modal -->


    <div class="navbar navbar-fixed-top navbar-inverse">
    <div class="navbar-inner">
    <div class="container"><a class="navbar-brand">Open Classifieds <?php echo __("Installation")?></a>
    <div class="nav-collapse">

    <div class="btn-group pull-right">
        <a class="btn btn-primary we-install" href="http://open-classifieds.com/market/">
            <i class="glyphicon-shopping-cart glyphicon"></i> <?php echo __("We install it for you, Buy now!")?>
        </a>
    </div>

    </div>
    <!--/.nav-collapse --></div>
    </div>
    </div>    
    <div class="container">
            <div class="row">
            
            <div class="col-md-3">
                <div class="well sidebar-nav">
                
                    <ul class="nav nav-list">
                        <li class="nav-header"><?php echo __("Requirements")?> OC v.<?php echo VERSION?></li>
                        <li class="divider"></li>
                        
                        <?php foreach ($checks as $name => $values):
                            if ($values['mandatory'] == TRUE AND $values['result'] == FALSE)
                                $succeed = FALSE;

                            if ($values['result'] == FALSE)
                                $msg .= $values['message'].'<br>';

                            $color = ($values['result'])?'success':'important';
                        ?>

                            <li><i class="glyphicon glyphicon-<?php echo ($values['result'])?"ok":"remove"?>"></i> 
                                <?php printf ('<span class="label label-%s">%s</span>',$color,$name);?>    
                            </li>
                        <?php endforeach?>

                        <li class="divider"></li>
                        <li><a href="#phpinfoModal" role="button" data-toggle="modal">PHP Info</a></li>
                        <li class="divider"></li>
                        
                        <li class="nav-header">Open Classifieds</li>
                        <li><a href="http://open-classifieds.com/market/">Market</a></li>
                        <li><a href="http://open-classifieds.com/">Support & More</a></li>
                        <li><a href="http://j.mp/thanksdonate" target="_blank">
                                <img src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" alt="">
                        </a></li>
                        <li class="divider"></li>
                        
                    </ul>
                    
                    <a href="https://twitter.com/openclassifieds"
                            onclick="javascript:_gaq.push(['_trackEvent','outbound-widget','http://twitter.com']);"
                            class="twitter-follow-button" data-show-count="false"
                            data-size="large">Follow @openclassifieds</a><br />
                        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    
                    
                </div>
                <!--/.well -->
            </div>
            <!--/span-->    

<div class="col-md-9">
<?php if ($_POST && $succeed):?>

    <?php if (!$install && !empty($error_msg)):?>
         <div class="alert alert-danger"><?php echo $error_msg?></div>
        <?php hostingAd()?>
    <?php elseif($install==TRUE):?>
        <div class="alert alert-success"><?php echo __('Congratulations');?></div>
        <div class="jumbotron">
            <h1><?php echo __('Installation done');?></h1>
            <p>
                <?php echo __('Please now erase the folder');?> <code>/install/</code><br>
            
                <a class="btn btn-success btn-large" href="<?php echo $_POST['SITE_URL']?>"><?php echo __('Go to Your Website')?></a>
                
                <a class="btn btn-warning btn-large" href="<?php echo $_POST['SITE_URL']?>oc-panel/home/">Admin</a> 
                <?php if($_POST['ADMIN_EMAIL'])?><span class="help-block">user: <?php echo $_POST['ADMIN_EMAIL']?> pass: <?php echo $_POST['ADMIN_PWD']?></span>
                <hr>
                <a class="btn btn-primary btn-large" href="http://j.mp/thanksdonate"><?php echo __('Make a donation')?></a>
                <?php echo __('We really appreciate it')?>.
            </p>
        </div>
    <?php endif?>

<?php elseif ($succeed):?>

<div class="page-header">
    <h1><?php echo __("Welcome to")?> Open Classifieds <?php echo __("installation")?></h1>
    <p>
        <?php echo __("Welcome to the super easy and fast installation")?>. 
            <a href="http://open-classifieds.com/market/" target="_blank">
            <?php echo __("If you need any help please check our professional services")?></a>.
    </p>    
</div>

<?php if ($msg){?>
    <div class="alert alert-warning"><?php echo $msg?></div>
<?php hostingAd();}?>

<form method="post" action="" class="well form-horizontal" >
<fieldset>

<h2><?php echo __('Site Configuration')?></h2>

<div class="form-group">
    
    <div class="col-md-6">
        <label class="control-label"><?php echo __("Site Language")?></label>
        <select class="form-control" name="LANGUAGE" onchange="window.location.href='?LANGUAGE='+this.options[this.selectedIndex].value">
            <?php 
            $languages = scandir("languages");
            foreach ($languages as $lang) 
            {    
                if( strpos($lang,'.')==false && $lang!='.' && $lang!='..' )
                {
                    $sel = ($lang==$locale_language) ? ' selected="selected"' : '';
                    echo "<option$sel value=\"$lang\">$lang</option>";
                }
            }
            ?>
        </select>
    </div>
</div>

<div class="form-group">
    
    <div class="col-md-6">
    <label class="control-label"><?php echo __("Site URL");?>:</label>
    <input  type="text" size="75" name="SITE_URL" value="<?php echo cP('SITE_NAME',$suggest_url)?>"  class="form-control" />
    </div>
</div>

<div class="form-group">
    
    <div class="col-md-6">
    <label class="control-label"><?php echo __("Site Name")?>:</label>
    <input  type="text" name="SITE_NAME" placeholder="<?php echo __("Site Name")?>" value="<?php echo cP('SITE_NAME')?>" class="form-control" />
    </div>
</div>

<div class="form-group">
    
    <div class="col-md-6">
    <label class="control-label"><?php echo __("Time Zone")?>:</label>
    <?php echo get_select_timezones('TIMEZONE',cP('TIMEZONE',date_default_timezone_get()))?>
    </div>
</div>


<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a href="#install" data-toggle="tab"><?php echo __('New Install')?></a></li>
  <li><a href="#upgrade" data-toggle="tab"><?php echo __('Upgrade System')?></a></li>
</ul>
 
<div class="tab-content">

    <div class="tab-pane active" id="install">
        <div class="form-group">
            
            <div class="col-md-6">
                <label class="control-label"><?php echo __("Administrator email")?>:</label>
                <input type="text" name="ADMIN_EMAIL" value="<?php echo cP('ADMIN_EMAIL')?>" placeholder="your@email.com" class="form-control" />
            </div>
        </div>

        <div class="form-group">
            
            <div class="col-md-6">
                <label class="control-label"><?php echo __("Admin Password")?>:</label>
                <input type="text" name="ADMIN_PWD" value="<?php echo cP('ADMIN_PWD')?>" class="form-control" />   
            </div>
        </div>

        <div class="checkbox">
            <label><input type="checkbox" name="SAMPLE_DB" checked /><?php echo __("Sample data")?></label>
            <span class="help-block"><?php echo __("Creates few sample categories to start with")?></span>
        </div>
        
    </div>

    <div class="tab-pane" id="upgrade">
        <div class="form-group">
            
            <div class="col-md-6">
                <label class="control-label"><?php echo __("Hash Key")?>:</label>
                <input type="text" name="HASH_KEY" value="<?php echo cP('HASH_KEY')?>" class="form-control" />   
                <span class="help-block"><?php echo __('You need the Hash Key to re-install. You can find this value if you lost it at')?> <code>/oc/config/auth.php</code></span>
            </div>
        </div>
    </div>

</div>


<h2><?php echo __('Database Configuration')?></h2>

<div class="form-group">
    
    <div class="col-md-6">
    <label class="control-label"><?php echo __("Host name")?>:</label>
    <input  type="text" name="DB_HOST" value="<?php echo cP('DB_HOST','localhost')?>" class="form-control"  />
    </div>
</div>

<div class="form-group">
    
    <div class="col-md-6">
    <label class="control-label"><?php echo __("User name")?>:</label>
    <input  type="text" name="DB_USER"  value="<?php echo cP('DB_USER','root')?>" class="form-control"   />
    </div>
</div>

<div class="form-group">
    
    <div class="col-md-6">
    <label class="control-label"><?php echo __("Password")?>:</label>
    <input type="text" name="DB_PASS" value="<?php echo cP('DB_PASS')?>" class="form-control" />       
    </div>
</div>

<div class="form-group">
    
    <div class="col-md-6">
    <label class="control-label"><?php echo __("Database name")?>:</label>
    <input type="text" name="DB_NAME" value="<?php echo cP('DB_NAME','openclassifieds')?>"  class="form-control"  />
    </div>
</div>

<div class="form-group">
    
    <div class="col-md-3">
    <label class="control-label"><?php echo __("Database charset")?>:</label>
    <input type="text" name="DB_CHARSET" value="<?php echo cP('DB_CHARSET','utf8')?>"  class="form-control"   />
    </div>
</div>

<div class="form-group">
    
    <div class="col-md-3">
    <label class="control-label"><?php echo __("Table prefix")?>:</label>
    <input type="text" name="TABLE_PREFIX" value="<?php echo cP('TABLE_PREFIX','oc2_')?>" class="form-control" />
    <span class="help-block"><?php echo __("Allows multiple installations in one database if you give each one a unique prefix")?>. <?php echo __("Only numbers, letters, and underscores")?>.</span>
    </div>
</div>


<div class="form-actions">

    <input type="submit" name="action" id="submit" value="<?php echo __("Install")?>" class="btn btn-primary btn-lg" />
    <hr>
    <div class="checkbox">
        <label>
            <input type="checkbox" name="OCAKU" checked />
            <?php echo __("Ocacu classifieds community registration")?> <a target="_blank" href="http://ocacu.com/en/terms.html">
            <br><?php echo __('Terms')?></a>
        </label>
    </div>
</div>

</fieldset>
</form>

<?php else:?>

    <div class="alert alert-danger"><?php echo $msg?></div>
    <?php hostingAd()?>

<?php endif?>

</div><!--/span--> 
</div><!--/row-->
<hr>

<footer>
<p>
&copy;  <a href="http://open-classifieds.com" title="Open Source PHP Classifieds">Open Classifieds</a> 2009 - <?php echo date('Y')?>
</p>
</footer>    

</div><!--/.fluid-container-->
    
        <script type="text/javascript" src="themes/default/js/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="themes/default/js/bootstrap.min.js"></script>

    <script type="text/javascript">
    $(function  () {
        $('.modal').css({
          'width': function () { 
            return ($(document).width() * .7) + 'px';  
          },
          // 'margin-left': function () { 
          //   return -($(this).width() / 2); 
          // },
          //'max-height': '800px';
        });
    })
    </script>
    <!--[if lt IE 7 ]>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>     <script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
    <![endif]-->
  </body>
</html>
