<?php 
/**
 * HTML template for the install
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

// Sanity check, install should only be checked from index.php
defined('SYSPATH') or exit('Install must be loaded from within index.php!');

//were the install files are located
define('INSTALLROOT', DOCROOT.'install/');

//we check first short tags if not we can not even load the installer
if (! ((bool) ini_get('short_open_tag')) )
    die('<strong><u>OC Installation requirement</u></strong>: Before you proceed with your OC installation: Keep in mind OC uses the short tag "short cut" syntax.<br><br> Thus the <a href="http://php.net/manual/ini.core.php#ini.short-open-tag" target="_blank">short_open_tag</a> directive must be enabled in your php.ini.<br><br><u>Easy Solution</u>:<ol><li>Open php.ini file and look for line short_open_tag = Off</li><li>Replace it with short_open_tag = On</li><li>Restart then your PHP server</li><li>Refresh this page to resume your OC installation</li><li>Enjoy OC ;)</li></ol>');

//prevents from new install to be done
if(!file_exists(INSTALLROOT.'install.lock')) 
    die('Installation seems to be done, please remove /install/ folder');

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);

include 'class.install.php';

//start the install setup
install::initialize();
$is_compatible = install::is_compatible();

//choosing what to display
//execute installation since they are posting data
if ( ($_POST OR isset($_GET['SITE_NAME'])) AND $is_compatible === TRUE)
{
    if (install::execute()===TRUE)
    {
        $data = array('site_url' => core::request('SITE_URL'), 'admin_email' => core::request('ADMIN_EMAIL'), 'admin_pwd' => core::request('ADMIN_PWD'));
        header('Content-Type: application/json');
        die(json_encode($data));
    }
    else
        $view = 'form';
}
//normally if its compaitble just display the form
elseif ($is_compatible === TRUE)
    $view = 'form';
//not compatible
else
    $view = 'hosting';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Open Classifieds <?=__("Installation")?></title>
    <meta name="keywords" content="" >
    <meta name="description" content="" >
    <meta name="copyright" content="Open Classifieds <?=install::VERSION?>" >
    <meta name="author" content="Open Classifieds">

    <link rel="shortcut icon" href="http://open-classifieds.com/wp-content/uploads/2012/04/favicon1.ico" />

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/chosen/1.1.0/chosen.min.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/animatecss/3.3.0/animate.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/css/selectize.bootstrap3.min.css" rel="stylesheet">
    <link href="install/css/install-style.css" rel="stylesheet">
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="container">
        <div class="row">
            <?if (!empty(install::$msg) OR !empty(install::$error_msg)):?>
                <div class="col-md-8 col-md-offset-2">
                    <div class="row alerts">
                        <div class="col-md-12">
                            <?install::view('hosting')?>
                        </div>
                    </div>
                </div>
            <?endif?>
            <div class="col-md-8 col-md-offset-2 animated fadeIn">
                <div class="row">
                    <div class="col-md-6">
                        <h2><a target="_blank" href="http://open-classifieds.com/"><img class="logo" src="http://open-classifieds.com/wp-content/uploads/2015/05/oc-logo-hd.png"></a></h2>
                        <br>
                        <p><strong><?=__("Welcome to the super easy and fast installation")?></strong></p>
                        <p>Open Classifieds is an open source powerful PHP classifieds script that can help you start a website and turn it into a fully customizable classifieds site within a few minutes.</p>
                        <br>
                        <p class="text-center"><strong><?=__('Can’t get it to work?')?></strong></p>
                        <p><a target="_blank" href="http://open-classifieds.com/market/" class="btn btn-default btn-large btn-block"><?=__("Get our professional services")?></a></p>
                    </div>
                    <div class="col-md-6">
                        <div class="off-canvas animated">
                            <form method="post" action="">
                                <div class="panel-1">
                                    <div class="form-group">
                                        <h3><?=__('Site Language')?></h3>
                                        <select id="LANGUAGE" name="LANGUAGE" class="form-control" onchange="window.location.href='?LANGUAGE='+this.options[this.selectedIndex].value" required>
                                            <?php
                                                $languages = scandir("languages");
                                                foreach ($languages as $lang) 
                                                {    
                                                    if (strpos($lang,'.')==false && $lang!='.' && $lang!='..' )
                                                    {
                                                        $sel = ( strtolower($lang)==strtolower(install::$locale)) ? ' selected="selected"' : '';
                                                        echo "<option$sel value=\"$lang\">$lang</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <h3><?=__('System Check')?></h3>
                                    <ul class="list-unstyled list-requirements">
                                        <?foreach (install::requirements() as $name => $values):
                                            $color = ($values['result'])?'success':'danger';?>
                                            <li data-color="<?=$color?>" data-result="<?=($values['result'])?"check":"times"?>">
                                                <span class="check">
                                                    <i class="fa fa-fw fa-spinner fa-pulse"></i>
                                                </span>
                                                <?=$name?>
                                            </li>
                                        <?endforeach?>
                                    </ul>
                                    <?if($is_compatible === TRUE):?>
                                        <p><button type="button" class="btn btn-default btn-block toggle-panel-1"><?=__('Start installation')?></button></p>
                                        <div class="panel-2">
                                            <h3><?=__('DB Configuration')?></h3>
                                            <div class="form-group">
                                                <label for="DB_HOST" class="control-label"><?=__("Host name")?></label>
                                                <input type="text" id="DB_HOST" name="DB_HOST" class="form-control" value="<?=core::request('DB_HOST','localhost')?>" required>
                                            </div>
                                            <div class="form-group">                
                                                <label for="DB_NAME" class="control-label"><?=__("Database name")?></label>
                                                <input type="text" id="DB_NAME" name="DB_NAME" class="form-control" value="<?=core::request('DB_NAME','openclassifieds')?>" required>
                                                <p class="help-block"><small><a target="_blank" href="http://open-classifieds.com/2014/02/24/create-mysql-database/"><?=__("How to create a MySQL database?")?></a></small></p>
                                            </div>
                                            <div class="form-group">                
                                                <label for="DB_USER" class="control-label"><?=__("User name")?></label>
                                                <input type="text" id="DB_USER" name="DB_USER" class="form-control"  value="<?=core::request('DB_USER','root')?>" required>
                                            </div>
                                            <div class="form-group">                
                                                <label for="DB_PASS" class="control-label"><?=__("Password")?></label>
                                                <input type="text" id="DB_PASS" name="DB_PASS" class="form-control" value="<?=core::request('DB_PASS')?>">
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="SAMPLE_DB" <?=(core::request('DB_CREATE'))?NULL:'checked'?>> <?=__("Sample data")?>
                                                </label>
                                            </div>
                                            <div class="checkbox hidden animated db-adv">
                                                <label>
                                                    <input type="checkbox" name="DB_CREATE" <?=(core::request('DB_CREATE'))?'checked':NULL?>> <?=__("Create DB")?>
                                                </label>
                                            </div>
                                            <div class="form-group hidden animated db-adv">
                                                <label for="DB_CHARSET" class="control-label"><?=__("Database charset")?>:</label>
                                                <input type="text" id="DB_CHARSET" name="DB_CHARSET" class="form-control" value="<?=core::request('DB_CHARSET','utf8')?>" required>
                                            </div>
                                            <div class="form-group hidden animated db-adv">
                                                <label form="TABLE_PREFIX" class="control-label"><?=__("Table prefix")?>:</label>
                                                <input type="text" id="TABLE_PREFIX" name="TABLE_PREFIX" class="form-control" value="<?=core::request('TABLE_PREFIX','oc2_')?>" required>
                                            </div>
                                            <p><button type="button" class="btn btn-default btn-block validate-db"><?=__('Continue')?></button></p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><button type="button" class="btn btn-link btn-xs toggle-panel-1"><i class="fa fa-long-arrow-left"></i> <?=__('Go back')?></button></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="text-right"><button type="button" id="show-adv-db" class="btn btn-link btn-xs"><?=__('Show advanced options')?></button></p>
                                                </div>
                                            </div>
    
                                            <div class="panel-3">
                                                <h3><?=__('Site Configuration')?></h3>
                                                <div class="form-group hidden animated site-adv">
                                                    <label for="SITE_URL" class="control-label"><?=__("Site URL");?>:</label>
                                                    <input type="text" id="SITE_URL" name="SITE_URL" class="form-control" size="75" value="<?=core::request('SITE_URL',install::$url)?>" required>
                                                </div>
                                                <div class="form-group hidden animated site-adv">
                                                    <label form="SITE_FOLDER" class="control-label"><?=__("Installation Folder");?>:</label>
                                                    <input type="text" id="SITE_FOLDER" name="SITE_FOLDER" class="form-control" size="75" value="<?=core::request('SITE_FOLDER',install::$folder)?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="SITE_NAME" class="control-label"><?=__("Site Name")?></label>
                                                    <input type="text" id="SITE_NAME" name="SITE_NAME" class="form-control" placeholder="<?=__("Site Name")?>" value="<?=core::request('SITE_NAME')?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="TIMEZONE" class="control-label"><?=__("Time Zone")?></label>
                                                    <?=install::get_select_timezones('TIMEZONE',core::request('TIMEZONE',date_default_timezone_get()))?>
                                                </div>
                                                <div class="form-group">
                                                    <label for="ADMIN_EMAIL" class="control-label"><?=__("Administrator email")?></label>
                                                    <input type="email" id="ADMIN_EMAIL" name="ADMIN_EMAIL" class="form-control" value="<?=core::request('ADMIN_EMAIL')?>" placeholder="your@email.com" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="ADMIN_PWD" class="control-label"><?=__("Admin Password")?></label>
                                                    <input type="text" id="ADMIN_PWD" name="ADMIN_PWD" class="form-control" value="<?=core::request('ADMIN_PWD')?>" required>
                                                </div>
                                                <div class="form-group hidden animated site-adv">
                                                    <label form="HASH_KEY" class="control-label"><?=__("Hash Key")?>:</label>
                                                    <input type="text" id="HASH_KEY" name="HASH_KEY" class="form-control" value="<?=core::request('HASH_KEY')?>">
                                                    <p class="help-block"><small><?=__('You need the Hash Key to re-install. You can find this value if you lost it at')?> <code>/oc/config/auth.php</code>.</small></p>
                                                </div>
                                                <p><button type="button" class="btn btn-default btn-block submit"><?=__('Install')?></button></p>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><button type="button" class="btn btn-link btn-xs toggle-panel-2"><i class="fa fa-long-arrow-left"></i> <?=__('Go back')?></button></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="text-right"><button type="button" id="show-adv-site" class="btn btn-link btn-xs"><?=__('Show advanced options')?></button></p>
                                                    </div>
                                                </div>
                                                <div class="panel-4">
                                                    <div class="scotch-panel-wrapper" style="position: relative; overflow: hidden; width: 100%;">
                                                        <h3><?=__('Congratulations');?></h3>
                                                        <div class="alert alert-success">
                                                            <p><strong><?=__('You thought there was more?')?></strong></p>
                                                            <p><?=__('Sorry there isn’t. Go to your website now!')?></p>
                                                        </div>
                                                        <div class="form-group">
                                                            <p class="form-control-static text-warning"><small><?=__('Please now erase the folder');?> <code>/install/</code></small></p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">user</label>
                                                            <p class="form-control-static admin_email"></p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">pass</label>
                                                            <p class="form-control-static admin_pwd"></p>
                                                        </div>
                                                        <p>
                                                            <a class="btn btn-default btn-block" href="<?=core::request('SITE_URL')?>"><?=__('Go to Your Website')?></a>
                                                        </p>
                                                        <p>
                                                            <a class="btn btn-default btn-block" href="<?=core::request('SITE_URL')?>oc-panel/home/">Admin</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?endif?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row copyright">
                    <div class="col-md-6">
                        <p>Copyright <a target="_blank" href="http://open-classifieds.com/">Open Classifieds</a></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-right">&copy; 2009-<?=date('Y')?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.min.js"></script>
    <script src="install/js/scotchPanels.min.js"></script>
    <script src="install/js/install.js"></script>

</body>
</html>
