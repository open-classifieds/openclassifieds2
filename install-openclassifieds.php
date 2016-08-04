<?php  
/**
 * Downloads OC and launches installer
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
ob_start(); 
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
@set_time_limit(0);

define('VERSION','2.1.1');


class OC{

    /**
     * copies files/directories recursively
     * @param  string  $source    from
     * @param  string  $dest      to
     * @param  boolean $overwrite overwrite existing file
     * @return void             
     */
    public static function copy($source, $dest, $overwrite = false)
    { 
        //Lets just make sure our new folder is already created. Alright so its not efficient to check each time... bite me
        if(is_file($dest))
        {
            copy($source, $dest);
            return;
        }
        
        if(!is_dir($dest))
            mkdir($dest);

        $objects = scandir($source);
        foreach ($objects as $object) 
        {
            if($object != '.' && $object != '..')
            { 
                $path = $source . '/' . $object; 
                if(is_file( $path))
                { 
                    if(!is_file( $dest . '/' . $object) || $overwrite) 
                    {
                        if(!@copy( $path,  $dest . '/' . $object))
                            die('File ('.$path.') could not be copied, likely a permissions problem.'); 
                    }
                }
                elseif(is_dir( $path))
                { 
                    if(!is_dir( $dest . '/' . $object)) 
                        mkdir( $dest . '/' . $object); // make subdirectory before subdirectory is copied 

                    OC::copy($path, $dest . '/' . $object, $overwrite); //recurse! 
                }
                 
            } 
        } 
        
    }

    /**
     * deletes file or directory recursevely
     * @param  string $file 
     * @return void       
     */
    public static function delete($file)
    {
        if (is_dir($file)) 
        {
            $objects = scandir($file);
            foreach ($objects as $object) 
            {
                if ($object != '.' && $object != '..') 
                {
                    if (is_dir($file.'/'.$object)) 
                        OC::delete($file.'/'.$object); 
                    else 
                        unlink($file.'/'.$object);
                }
            }
            reset($objects);
            @rmdir($file);
        }
        elseif(is_file($file))
            unlink($file);
    }


    /**
     * gets the html content from a URL
     * @param  string $url 
     * @return string      
     */
    public static function curl_get_contents($url)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_TIMEOUT,5); 
        $contents = curl_exec($c);
        curl_close($c);

        return ($contents)? $contents : FALSE;
    }

    /**
     * checs that your hosting has everything that needs to have
     * @return array 
     */
    public static function requirements()
    {

        /**
         * mod rewrite check
         */
        if(function_exists('apache_get_modules'))
        {
            $mod_msg        = 'OC Requires Apache mod_rewrite module to be installed';
            $mod_mandatory  = TRUE;

            if (in_array('mod_rewrite',apache_get_modules()))
                $mod_result = TRUE;
            else 
                $mod_result     = FALSE;
            
        }
        //in case they dont use apache a nicer message
        else 
        {
            $mod_msg        = 'Can not check if mod_rewrite installed, probably everything is fine. Try to proceed with the installation anyway ;)';
            $mod_mandatory  = FALSE;
            $mod_result     = FALSE;
        }
                
                
        /**
         * all the install checks
         */
        return     array(

                    'New Installation'=>array('message'   => 'Seems Open Classifieds it is already insalled',
                                        'mandatory' => TRUE,
                                        'result'    => !file_exists('oc/config/database.php')
                                        ),
                    'Write DIR'       =>array('message'   => 'Can\'t write to the current directory. Please fix this by giving the webserver user write access to the directory.',
                                        'mandatory' => TRUE,
                                        'result'    => (is_writable('.'))
                                        ),
                    'PHP'   =>array('message'   => 'PHP 5.3 or newer required, this version is '. PHP_VERSION,
                                        'mandatory' => TRUE,
                                        'result'    => version_compare(PHP_VERSION, '5.3', '>=')
                                        ),
                    'mod_rewrite'=>array('message'  => $mod_msg,
                                        'mandatory' => $mod_mandatory,
                                        'result'    => $mod_result
                                        ),
                    'Short Tag'   =>array('message'   => '<a href="http://www.php.net/manual/en/ini.core.php#ini.short-open-tag">short_open_tag</a> must be enabled in your php.ini.',
                                        'mandatory' => TRUE,
                                        'result'    => (bool) ini_get('short_open_tag')
                                        ),
                    'PCRE UTF8' =>array('message'   => '<a href="http://php.net/pcre">PCRE</a> has not been compiled with UTF-8 support.',
                                        'mandatory' => TRUE,
                                        'result'    => (bool) (@preg_match('/^.$/u', 'ñ'))
                                        ),
                    'PCRE Unicode'=>array('message' => '<a href="http://php.net/pcre">PCRE</a> has not been compiled with Unicode property support.',
                                        'mandatory' => TRUE,
                                        'result'    => (bool) (@preg_match('/^\pL$/u', 'ñ'))
                                        ),
                    'SPL'       =>array('message'   => 'PHP <a href="http://www.php.net/spl">SPL</a> is either not loaded or not compiled in.',
                                        'mandatory' => TRUE,
                                        'result'    => (function_exists('spl_autoload_register'))
                                        ),
                    'Reflection'=>array('message'   => 'PHP <a href="http://www.php.net/reflection">reflection</a> is either not loaded or not compiled in.',
                                        'mandatory' => TRUE,
                                        'result'    => (class_exists('ReflectionClass'))
                                        ),
                    'Filters'   =>array('message'   => 'The <a href="http://www.php.net/filter">filter</a> extension is either not loaded or not compiled in.',
                                        'mandatory' => TRUE,
                                        'result'    => (function_exists('filter_list'))
                                        ),
                    'Iconv'     =>array('message'   => 'The <a href="http://php.net/iconv">iconv</a> extension is not loaded.',
                                        'mandatory' => TRUE,
                                        'result'    => (extension_loaded('iconv'))
                                        ),
                    'Mbstring'  =>array('message'   => 'The <a href="http://php.net/mbstring">mbstring</a> extension is not loaded.',
                                        'mandatory' => TRUE,
                                        'result'    => (extension_loaded('mbstring'))
                                        ),
                    'CType'     =>array('message'   => 'The <a href="http://php.net/ctype">ctype</a> extension is not enabled.',
                                        'mandatory' => TRUE,
                                        'result'    => (function_exists('ctype_digit'))
                                        ),
                    'URI'       =>array('message'   => 'Neither <code>$_SERVER[\'REQUEST_URI\']</code>, <code>$_SERVER[\'PHP_SELF\']</code>, or <code>$_SERVER[\'PATH_INFO\']</code> is available.',
                                        'mandatory' => TRUE,
                                        'result'    => (isset($_SERVER['REQUEST_URI']) OR isset($_SERVER['PHP_SELF']) OR isset($_SERVER['PATH_INFO']))
                                        ),
                    'cUrl'      =>array('message'   => 'OC requires the <a href="http://php.net/curl">cURL</a> extension for the Request_Client_External class.',
                                        'mandatory' => TRUE,
                                        'result'    => (extension_loaded('curl'))
                                        ),
                    'mcrypt'    =>array('message'   => 'OC requires the <a href="http://php.net/mcrypt">mcrypt</a> for the Encrypt class.',
                                        'mandatory' => TRUE,
                                        'result'    => (extension_loaded('mcrypt'))
                                        ),
                    'GD'        =>array('message'   => 'OC requires the <a href="http://php.net/gd">GD</a> v2 for the Image class',
                                        'mandatory' => TRUE,
                                        'result'    => (function_exists('gd_info'))
                                        ),
                    'MySQL'     =>array('message'   => 'OC requires the <a href="http://php.net/mysql">MySQL</a> extension to support MySQL databases.',
                                        'mandatory' => TRUE,
                                        'result'    => (function_exists('mysql_connect'))
                                        ),
                    'ZipArchive'   =>array('message'   => 'PHP module zip not installed. Please ask your server administrator to install the module.',
                                        'mandatory' => TRUE,
                                        'result'    => class_exists('ZipArchive')
                                        ),
                    );
    }

    /**
     * returns array last version from json
     * @return array
     */
    public static function versions()
    {
        return json_decode(OC::curl_get_contents('http://open-classifieds.com/files/versions.json?r='.time()),TRUE);
    }
}



/**
 * suggested hosting from OC
 * @return HTML 
 */
function hostingAd()
{
    ?>
    <div class="hero-unit">
        <h2>Ups! You need a compatible Hosting</h2>
        <p class="text-error">Your hosting seems to be not compatible. Check your settings.<p>
        <p>We have partnership with hosting companies to assure compatibility. And we include:
            <ul>
                <li>100% Compatible High Speed Hosting</li>
                <li>1 Premium Theme, of your choice worth $99.99</li>
                <li>Professional Installation and Support worth $89</li>
                <li>Free Domain name, worth $10</li>
            <a class="btn btn-primary btn-large" href="http://open-classifieds.com/hosting/">
                <i class=" icon-shopping-cart icon-white"></i> Get Hosting! Less than $5 Month</a>
        </p>
    </div>
    <?php
}


//read from oc/versions.json on CDN
$versions   = OC::versions();
$last_version = key($versions);
$checks     = OC::requirements();
$msg        = NULL;    
$succeed    = TRUE; 

?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en>"> <!--<![endif]-->
<head>
    <meta charset="utf8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Open Classifieds Installation</title>
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
    </style>
        
    <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">

  </head>

  <body>

    <!--phpinfo Modal -->
    <div id="phpinfoModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-body">
        <?php 
        //getting the php info clean!
        ob_start();                                                                                                        
        phpinfo();                                                                                                     
        $phpinfo = ob_get_contents();                                                                                         
        ob_end_clean();  
        //strip the body html                                                                                                  
        $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
        //adding our class
        echo str_replace('<table', '<table class="table table-striped  table-bordered"', $phpinfo);
        ?>
      </div>
    </div>
    <!--END phpinfo Modal -->


    <div class="navbar navbar-fixed-top navbar-inverse">
    <div class="navbar-inner">
    <div class="container"><a class="brand">Open Classifieds Installation</a>
    <div class="nav-collapse">

    <div class="btn-group pull-right">
        <a class="btn btn-primary" href="http://open-classifieds.com/market/">
            <i class="icon-shopping-cart icon-white"></i> We install it for you, Buy now!
        </a>
    </div>

    </div>
    <!--/.nav-collapse --></div>
    </div>
    </div>    
    <div class="container">
            <div class="row">
            
            <div class="span3">
                <div class="well sidebar-nav">
                
                    <ul class="nav nav-list">
                        <li class="nav-header">Requirements <?php echo VERSION?></li>
                        <li class="divider"></li>
                        
                        <?php foreach ($checks as $name => $values):
                            if ($values['mandatory'] == TRUE AND $values['result'] == FALSE)
                                $succeed = FALSE;

                            if ($values['result'] == FALSE)
                                $msg .= $values['message'].'<br>';

                                $color = $values['result']?'success':'important'; // http://getbootstrap.com/2.3.2/components.html#labels-badges
                        ?>

                            <li><i class="icon-<?php echo ($values['result'])?"ok":"remove"?>"></i> 
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

<div class="span9">
<?php if ($_POST && $succeed):?>
    <?php
        //theres post, download latest version, unzip and rediret to install
        //download file
        file_put_contents('oc.zip', fopen($versions[$last_version]['download'], 'r'));
        $fname = 'openclassifieds2-'.$last_version;

        $zip = new ZipArchive;
        // open zip file, extract to dir
        if ($zip_open = $zip->open('oc.zip')) 
        {
            $zip->extractTo('./');
            $zip->close();  
            @unlink('oc.zip');
            OC::copy($fname, './');
            OC::delete($fname);
            // delete own file
            @unlink($_SERVER['SCRIPT_FILENAME']);
            
            // redirect to install
            header("Location: index.php");    
        }   
        else 
        {
            hostingAd();
        }
    ?>

<?php elseif ($succeed):?>

<div class="page-header">
    <h1>Welcome to Open Classifieds installation</h1>
    <p>
        Welcome to the super easy and fast installation. 
            <a href="http://open-classifieds.com/market/" target="_blank">
            If you need any help please check our professional services</a>.
    </p>    
</div>

<?php if ($msg!=NULL){?>
    <div class="alert alert-warning"><?php echo $msg?></div>
<?php hostingAd();}?>

<form method="post" action="" class="well" >
<fieldset>
    <h3>We are going to install to you <?php echo $last_version;?></h3>
<div class="form-action">
<input type="submit" name="action" id="submit" value="Download and Install" class="btn btn-primary btn-large" />
</div>

</fieldset>
</form>

<?php else:?>

    <div class="alert alert-error"><?php echo $msg?></div>
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
    
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>

    <script type="text/javascript">
    $(function  () {
        $('.modal').css({
          'width': function () { 
            return ($(document).width() * .7) + 'px';  
          },
          'margin-left': function () { 
            return -($(this).width() / 2); 
          },
          //'max-height': '800px';
        });
    })
    </script>
    <!--[if lt IE 7 ]>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>     <script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
    <![endif]-->
  </body>
</html>