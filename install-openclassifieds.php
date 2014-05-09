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

ob_start(); 
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
@set_time_limit(0);
// Set the full path to the docroot
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

//we check first short tags if not we can not even load the installer
if (! ((bool) ini_get('short_open_tag')) )
    die('<strong><u>OC Installation requirement</u></strong>: Before you proceed with your OC installation: Keep in mind OC uses the short tag "short cut" syntax.<br><br> Thus the <a href="http://php.net/manual/ini.core.php#ini.short-open-tag" target="_blank">short_open_tag</a> directive must be enabled in your php.ini.<br><br><u>Easy Solution</u>:<ol><li>Open php.ini file and look for line short_open_tag = Off</li><li>Replace it with short_open_tag = On</li><li>Restart then your PHP server</li><li>Refresh this page to resume your OC installation</li><li>Enjoy OC ;)</li></ol>');

//prevents from new install to be done
if (file_exists(DOCROOT.'oc/config/database.php'))
    die('It seems Open Classifieds is already installed');


//read from oc/versions.json on CDN
$versions       = install::versions();
$last_version   = key($versions);
$is_compatible  = install::is_compatible();


/**
 * Helper installation classses
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */


/**
 * Class with install functions helper
 */
class install{
    
    /**
     * 
     * Software install settings
     * @var string
     */
    const version   = '2.1.6';

    /**
     * message to notify
     * @var string
     */
    public static $msg = '';

     /**
      * installation error messages here
      * @var string
      */
    public static $error_msg  = '';

    /**
     * checks that your hosting has everything that needs to have
     * @return array 
     */
    public static function requirements()
    {

        /**
         * mod rewrite check
         */
        if(function_exists('apache_get_modules'))
        {
            $mod_msg        = 'Install requires Apache mod_rewrite module to be installed';
            $mod_mandatory  = TRUE;
            $mod_result     = in_array('mod_rewrite',apache_get_modules());
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
                'New Installation'=>array('message'   => 'It seems that Open Classifieds is already installed',
                                        'mandatory' => TRUE,
                                        'result'    => !file_exists('oc/config/database.php')
                                        ),
                'Write DIR'       =>array('message'   => 'Can\'t write to the current directory. Please fix this by giving the webserver user write access to the directory.',
                                        'mandatory' => TRUE,
                                        'result'    => (is_writable(DOCROOT))
                                        ),
                'PHP'   =>array('message'   => 'PHP 5.3 or newer is required, this version is '. PHP_VERSION,
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
                'Safe Mode'   =>array('message'   => '<a href="http://php.net/manual/en/features.safe-mode.php>safe_mode</a> must be disabled.',
                                        'mandatory' => TRUE,
                                        'result'    => ((bool) ini_get('safe_mode'))?FALSE:TRUE
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
                'cUrl'      =>array('message'   => 'Install requires the <a href="http://php.net/curl">cURL</a> extension for the Request_Client_External class.',
                                    'mandatory' => TRUE,
                                    'result'    => (extension_loaded('curl'))
                                    ),
                'mcrypt'    =>array('message'   => 'Install requires the <a href="http://php.net/mcrypt">mcrypt</a> for the Encrypt class.',
                                    'mandatory' => TRUE,
                                    'result'    => (extension_loaded('mcrypt'))
                                    ),
                'GD'        =>array('message'   => 'Install requires the <a href="http://php.net/gd">GD</a> v2 for the Image class',
                                    'mandatory' => TRUE,
                                    'result'    => (function_exists('gd_info'))
                                    ),
                'MySQL'     =>array('message'   => 'Install requires the <a href="http://php.net/mysqli">MySQLi</a> extension to support MySQL databases.',
                                    'mandatory' => TRUE,
                                    'result'    => (function_exists('mysqli_connect'))
                                    ),
                'ZipArchive'   =>array('message'   => 'PHP module zip not installed. You will need this to auto update the software.',
                                    'mandatory' => FALSE,
                                    'result'    => class_exists('ZipArchive')
                                    ),
                );
    }

    /**
     * checks from requirements if its compatible or not. Also fills the msg variable
     * @return boolean 
     */
    public static function is_compatible()
    {
        self::$msg = '';
        $compatible = TRUE;
        foreach (install::requirements() as $name => $values)
        {
            if ($values['mandatory'] == TRUE AND $values['result'] == FALSE)
                $compatible = FALSE;

            if ($values['result'] == FALSE)
                self::$msg .= $values['message'].'<br>';
        }

        return $compatible;
            
    }


    /**
     * get phpinfo clean in a string
     * @return strin 
     */
    public static function phpinfo()
    {
        ob_start();                                                                                                        
        @phpinfo();                                                                                                     
        $phpinfo = ob_get_contents();                                                                                         
        ob_end_clean();  
        //strip the body html                                                                                                  
        return preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
    }

    /**
     * returns array of last versions from json
     * @return array
     */
    public static function versions()
    {
        return json_decode(core::curl_get_contents('http://open-classifieds.com/files/versions.json?r='.time()),TRUE);
    }


}

class core{

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

                    core::copy($path, $dest . '/' . $object, $overwrite); //recurse! 
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
                if ($object != '.' AND $object != '..') 
                {
                    if (is_dir($file.'/'.$object)) 
                        core::delete($file.'/'.$object); 
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
        curl_setopt($c, CURLOPT_TIMEOUT,30); 
        // curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);
        // $contents = curl_exec($c);
        $contents = core::curl_exec_follow($c);
        curl_close($c);

        return ($contents)? $contents : FALSE;
    }

    /**
     * [curl_exec_follow description] http://us2.php.net/manual/en/function.curl-setopt.php#102121
     * @param  curl  $ch          handler
     * @param  integer $maxredirect hoe many redirects we allow
     * @return contents
     */
    public static function curl_exec_follow($ch, $maxredirect = 5) 
    { 
        //using normal curl redirect
        if (ini_get('open_basedir') == '' AND ini_get('safe_mode' == 'Off')) 
        { 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $maxredirect > 0); 
            curl_setopt($ch, CURLOPT_MAXREDIRS, $maxredirect); 
        } 
        //using safemode...WTF!
        else 
        { 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE); 
            if ($maxredirect > 0) 
            { 
                $newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); 

                $rch = curl_copy_handle($ch); 
                curl_setopt($rch, CURLOPT_HEADER, TRUE); 
                curl_setopt($rch, CURLOPT_NOBODY, TRUE); 
                curl_setopt($rch, CURLOPT_FORBID_REUSE, FALSE); 
                curl_setopt($rch, CURLOPT_RETURNTRANSFER, TRUE); 

                do 
                { 
                    curl_setopt($rch, CURLOPT_URL, $newurl); 
                    $header = curl_exec($rch); 
                    if (curl_errno($rch))
                        $code = 0; 
                    else 
                    { 
                        $code = curl_getinfo($rch, CURLINFO_HTTP_CODE); 
                        if ($code == 301 OR $code == 302) 
                        { 
                            preg_match('/Location:(.*?)\n/', $header, $matches); 
                            $newurl = trim(array_pop($matches)); 
                        }
                        else 
                            $code = 0; 
                    } 
                } 
                while ($code AND --$maxredirect); 

                curl_close($rch); 

                if (!$maxredirect) 
                { 
                    if ($maxredirect === NULL) 
                        trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING); 
                    else  
                        $maxredirect = 0; 

                    return FALSE; 
                } 

                curl_setopt($ch, CURLOPT_URL, $newurl); 
            } 
        } 

        return curl_exec($ch); 
    } 

    /**
     * rss reader
     * @param  string $url 
     * @return array      
     */
    public static function rss($url)
    {
        $items = array();

        $rss = simplexml_load_file($url);
        if($rss)
            $items = $rss->channel->item;

        return $items;
    }

    /**
     * shortcut for the query method $_GET
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function get($key,$default=NULL)
    {
        return (isset($_GET[$key]))?$_GET[$key]:$default;
    }

    /**
     * shortcut for $_POST[]
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function post($key,$default=NULL)
    {
        return (isset($_POST[$key]))?$_POST[$key]:$default;
    }

    /**
     * shortcut to get or post
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function request($key,$default=NULL)
    {
        return (core::post($key)!==NULL)?core::post($key):core::get($key,$default);
    }
}

/**
 * gettext short cut currently just echoes
 * @param  [type] $msgid [description]
 * @return [type]        [description]
 */
function __($msgid)
{
    return $msgid;
}
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en>"> <!--<![endif]-->
<head>
    <meta charset="utf8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Open Classifieds <?=__("Installation")?></title>
    <meta name="copyright" content="Open Classifieds <?=install::version?>" >
    <meta name="author" content="Open Classifieds">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="shortcut icon" href="http://open-classifieds.com/wp-content/uploads/2012/04/favicon1.ico" />

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>    <![endif]-->
       
    <style type="text/css">
    body {
        padding-top: 60px;
        padding-bottom: 40px;
    }

    .sidebar-nav {
        padding: 9px 0;
    }
    .chosen-single{padding: 4px 0px 27px 8px!important;}
    .chosen-single b{margin: 4px!important;}
    .navbar-brand{padding: 4px 50px 0px 0px!important;}
    .we-install{padding: 11px!important;margin-top: 7px;}
    .adv{display: none;}
    .logo img {margin-top: 10px;}
    .page-header{margin: 25px 0 21px!important;}
    .mb-10{margin-bottom: 10px!important;}
    #myTab{margin-top: 14px;}

    </style>
        
    <link href="//netdna.bootstrapcdn.com/bootswatch/3.1.1/flatly/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chosen/1.1.0/chosen.min.css">

</head>

<body>
    <div class="container">
        <div class="navbar navbar-fixed-top navbar-inverse">

            <div class="navbar-inner">
                <div class="container">
                    <button class="navbar-toggle pull-left" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                        <span class="sr-only"><?=__("Toggle navigation")?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-collapse bs-navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="#home" data-toggle="tab"><?=__("Install")?></a></li>
                            <li><a href="http://open-classifieds.com/support/" target="_blank"><?=__("Support")?></a></li>
                            <li><a href="#home" data-toggle="tab"><?=__("Requirements")?></a></li>
                            <li><a href="#about" data-toggle="tab"><?=__("About")?></a></li>
                        </ul>

                        <div class="btn-group pull-right">
                            <a class="btn btn-primary we-install" href="http://open-classifieds.com/market/">
                                <i class="glyphicon-shopping-cart glyphicon"></i> <?=__("We install it for you, Buy now!")?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <a class="logo" target="_blank" href="http://open-classifieds.com">
            <img src="http://open-classifieds.com/wp-content/uploads/2012/04/OC_noTagline_286x52.png" alt="Open Classifieds <?=__("Installation")?>">
        </a>    
        <div class="tab-content">

            <div class="tab-pane fade in active" id="home">
                <?
                //choosing what to display
                //execute installation since they are posting data
                if ( $_POST  AND $is_compatible === TRUE)
                {
                    //theres post, download latest version, unzip and rediret to install
                    //download file
                    $file_content = core::curl_get_contents($versions[$last_version]['download']);
                    file_put_contents('oc.zip', $file_content);
                    $fname = 'openclassifieds2-'.$last_version;

                    $zip = new ZipArchive;
                    // open zip file, extract to dir
                    if ($zip_open = $zip->open('oc.zip')) 
                    {
                        $zip->extractTo(DOCROOT);
                        $zip->close();  
                        
                        core::copy($fname, DOCROOT);
                        
                        // delete own file
                        core::delete($fname);
                        @unlink('oc.zip');
                        @unlink($_SERVER['SCRIPT_FILENAME']);
                        
                        // redirect to install
                        header("Location: index.php");    
                    }   
                    else 
                        hosting_view();
                }
                //normally if its compaitble just display the form
                elseif ($is_compatible === TRUE)
                {?>
                    <?if (!empty(install::$msg) OR !empty(install::$error_msg)) 
                            hosting_view();?>
                    <div class="page-header">
                        <h1><?=__("Install Open Classifieds")?> v.<?=$last_version;?></h1>
                        <p><?=__("We will download last stable version of Open Classifieds and redirect you to the installation form.")?><br>
                            <?=__("Once you click in the install button can take few seconds until downloaded, please do not close this window.")?></p>
                        <div class="clearfix"></div>
                    </div>
                    <form method="post" action="" class="" >
                        <fieldset>
                            <div class="form-action">
                            <input type="submit" name="action" id="submit" value=<?=HTML::chars(__("Download and Install"))?>" class="btn btn-primary btn-large" />
                            </div>
                        </fieldset>
                    </form>
                <?}
                //not compatible
                else
                    hosting_view();
                ?>
                    <hr>
                    <h3><?=__("Software Requirements")?>  v.<?=$last_version;?></h3>
                    <p><?=__('Requirements checks we do before we install.')?> 
                        <span class="label label-info" id="phpinfobutton" >phpinfo()</span>
                    </p>

                    <?foreach (install::requirements() as $name => $values):
                        $color = ($values['result'])?'success':'danger';?>
                        <div class="pull-left <?=$color?>" style=" width: 100px; height: 56px; text-align: center;">
                            <h4><i class="glyphicon glyphicon-<?=($values['result'])?"ok":"remove"?>"></i>
                            <div class="clearfix"></div> 
                            <?printf ('<span class="label label-%s">%s</span>',$color,$name);?> </h4>
                        </div>   
                    <?endforeach?>
        
                    <div class="clearfix"></div><br>

                    <div class="hidden" id="phpinfo">
                        <?=str_replace('<table', '<table class="table table-striped table-bordered"', install::phpinfo())?>
                    </div>
            </div>

            <div class="tab-pane fade" id="about">
                <div class="page-header">
                    <h1><?=__('Welcome')?> </h1>
                    <p><?=__('Thanks for using Open Classifieds.')?> 
                        <?=__('Your installation version is')?> <span class="label label-info"><?=install::version?></span> 
                    </p>
                    
                    <div class="clearfix"></div>
                    <p><?=__('You need help or you have some questions')?>
                        <a class="btn btn-info btn-xs" target="_blank" href="http://forums.open-classifieds.com/"><i class="glyphicon glyphicon-wrench"></i> <?=__('Forum')?></a>
                        <a class="btn btn-info btn-xs" target="_blank" href="http://open-classifieds.com/support/"><i class="glyphicon glyphicon-question-sign"></i> <?=__('FAQ')?></a>
                        <a class="btn btn-info btn-xs" target="_blank" href="http://open-classifieds.com/blog/"><i class="glyphicon glyphicon-pencil"></i> <?=__('Blog')?></a>
                    </p>
                </div>

                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="panel panel-info">
                    <div class="panel-heading"><h3>Open-Classifieds <?=__('Latest News')?></h3>
                    </div>
                        <div class="panel-body">
                            <ul>
                                <?foreach (core::rss('http://feeds.feedburner.com/OpenClassifieds')  as $item):?>
                                    <li><a target="_blank" href="<?=$item->link?>" title="<?=$item->title?>"><?=$item->title?></a></li>
                                    <div class="divider"></div>
                                <?endforeach?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <a class="twitter-timeline" href="https://twitter.com/openclassifieds" data-widget-id="428842439499997185">Tweets by @openclassifieds</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fopenclassifieds&amp;width=350&amp;height=600&amp;colorscheme=dark&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=true&amp;appId=181472118540903" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:350px; height:600px;" allowTransparency="true"></iframe>    
                </div>
            </div>
        </div>
           
        <hr>

        <footer>
            <p>
            &copy; <a href="http://open-classifieds.com" title="Open Source PHP Classifieds" target="_blank">Open Classifieds</a> 2009 - <?=date('Y')?>
            </p>
        </footer>
    </div> 
    
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/jquery.bootstrapvalidation/1.3.7/jqBootstrapValidation.min.js"></script>
    <script src="//cdn.jsdelivr.net/chosen/1.1.0/chosen.jquery.min.js"></script>

    <script>
        $(function () { 
            $("select").chosen();
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); 
            $('input, select').tooltip(); 
        });

        $('#advanced-options').click(function(){
            if($(this).hasClass('btn-primary'))
            {
                $(this).removeClass('btn-primary');
                $(this).addClass('btn-default');
                $('.adv').each(function(){
                    $(this).hide();
                });
                $('#myTab').css('display','none');
            }
            else
            {
                $(this).removeClass('btn-default');
                $(this).addClass('btn-primary');
                $('.adv').each(function(){
                    $(this).show();
                });
                $('#myTab').css('display','block');  
            }
        });

        $('#phpinfobutton').click(function(){
            if($('#phpinfo').hasClass('hidden'))
            {
                $(this).removeClass('label-info');
                $(this).addClass('label-warning');
                $('#phpinfo').removeClass('hidden');
            }
            else
            {
                $(this).removeClass('label-warning');
                $(this).addClass('label-info');
                $('#phpinfo').addClass('hidden');
            }
        });

    </script>

</body>
</html>

<?
/**
 * displayed in case not compatible
 * @return [type] [description]
 */
function hosting_view()
{
    ?>
    <?if (!empty(install::$error_msg)):?>
    <br>
    <div class="alert alert-danger"><?=install::$error_msg?></div>
    <?endif?>

    <?if(!empty(install::$msg)):?>
        <br>
        <div class="alert alert-warning">
            <?=__("We have detected some incompatibilities, installation may not work as expected but you can try.")?> <br>
            <?=install::$msg?>
        </div>
    <?endif?>

    <div class="jumbotron well">
        <h2><?=__("Oops! You need a compatible Hosting")?></h2>
        <p class="text-danger"><?=__("Your hosting seems to be not compatible. Check your settings.")?><p>
        <p><?=__("We have partnership with hosting companies to assure compatibility. And we include:")?></p>
            <ul>
                <li><?=__("100% Compatible High Speed Hosting")?></li>
                <li><?=sprintf(_("%s Premium Theme%s of your choice, worth %s"),'1','','$129.99')?></li>
                <li><?=sprintf(_("Professional Installation and Support worth %s"),'$89')?></li>
                <li><?=sprintf(_("Free Domain name, worth %s"),'$10')?></li>
                <div class="clearfix"></div><br>
            <a class="btn btn-primary btn-large" href="http://open-classifieds.com/hosting/">
                <i class=" icon-shopping-cart icon-white"></i> Get Hosting! Less than $5 Month</a>
        </p>
    </div>
    <?
}
?>
