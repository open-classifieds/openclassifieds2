<?php
/**
 * Helper include to set up install
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

/**
 * *************************************************************
 * Initial variables to isntall
 * *************************************************************
 */


// Sanity check, install should only be checked from index.php
defined('SYSPATH') or exit('Install must be loaded from within index.php!');

//prevents from new install to be done
if(!file_exists(DOCROOT.'install/install.lock')) die('Installation seems to be done, please remove /install/ folder');

define('VERSION','2.1.1');


//Gets language to use
if      (isset($_POST['LANGUAGE'])) $locale_language=$_POST['LANGUAGE'];
elseif  (isset($_GET['LANGUAGE'])) $locale_language=$_GET['LANGUAGE'];
else    $locale_language='en_US';//default

//start translations
gettext_init($locale_language);


// Try to guess installation URL
    $suggest_url = 'http://'.$_SERVER['SERVER_NAME'];
    if ($_SERVER['SERVER_PORT'] != '80') 
        $suggest_url = $suggest_url.':'.$_SERVER['SERVER_PORT'];
    //getting the folder, erasing the index
    $suggest_folder = str_replace('/index.php','', $_SERVER['SCRIPT_NAME']).'/';
    $suggest_url .=$suggest_folder;


//bool to see if the isntallation was good
    $install = FALSE;
//installation error messages here
    $error_msg  = '';

//requirements checks correct?
    $succeed = TRUE; 
//message to explain what was not correct
    $msg     = '';

//Software requirements
$checks = oc_requirements();


/**
 * *************************************************************
 * Functions to help in the installation
 * *************************************************************
 */


/**
 * checs that your hosting has everything that needs to have
 * @return array 
 */
function oc_requirements()
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

                'robots.txt'=>array('message'   => 'The <code>'.DOCROOT.'robots.txt</code> file is not writable.',
                                    'mandatory' => FALSE,
                                    'result'    => is_writable(DOCROOT.'robots.txt')
                                    ),
                '.htaccess' =>array('message'   => 'The <code>'.DOCROOT.'.htaccess</code> file is not writable.',
                                    'mandatory' => TRUE,
                                    'result'    => is_writable(DOCROOT.'.htaccess')
                                    ),
                'sitemap'   =>array('message'   => 'The <code>'.DOCROOT.'sitemap.xml.gz</code> file is not writable.',
                                    'mandatory' => FALSE,
                                    'result'    => is_writable(DOCROOT.'sitemap.xml.gz')
                                    ),
                'images'    =>array('message'   => 'The <code>'.DOCROOT.'images/</code> directory is not writable.',
                                    'mandatory' => TRUE,
                                    'result'    => is_writable(DOCROOT.'images')
                                    ),
                'themes'    =>array('message'   => 'The <code>'.DOCROOT.'themes/</code> directory is not writable.',
                                    'mandatory' => TRUE,
                                    'result'    => is_writable(DOCROOT.'themes')
                                    ),
                'cache'     =>array('message'   => 'The <code>'.APPPATH.'cache/</code> directory is not writable.',
                                    'mandatory' => TRUE,
                                    'result'    => (is_dir(APPPATH) AND is_dir(APPPATH.'cache') AND is_writable(APPPATH.'cache'))
                                    ),
                'logs'      =>array('message'   => 'The <code>'.APPPATH.'logs/</code> directory is not writable.',
                                    'mandatory' => TRUE,
                                    'result'    => (is_dir(APPPATH) AND is_dir(APPPATH.'logs') AND is_writable(APPPATH.'logs'))
                                    ),
                'SYS'       =>array('message'   => 'The configured <code>'.SYSPATH.'</code> directory does not exist or does not contain required files.',
                                    'mandatory' => TRUE,
                                    'result'    => (is_dir(SYSPATH) AND is_file(SYSPATH.'classes/kohana'.EXT))
                                    ),
                'APP'       =>array('message'   => 'The configured <code>'.APPPATH.'</code> directory does not exist or does not contain required files.',
                                    'mandatory' => TRUE,
                                    'result'    => (is_dir(APPPATH) AND is_file(APPPATH.'bootstrap'.EXT))
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
                'ZipArchive'   =>array('message'   => 'PHP module zip not installed. You will need this to auto update the software.',
                                    'mandatory' => FALSE,
                                    'result'    => class_exists('ZipArchive')
                                    ),
                );
}


/**
 * loads gettexts or droppin
 * @param  string $locale  
 * @param  string $domain  
 * @param  string $charset 
 */
function gettext_init($locale,$domain = 'messages',$charset = 'utf8')
{
    /**
     * check if gettext exists if not uses gettext dropin
     */
    $locale_res = setlocale(LC_MESSAGES, $locale);
    if ( !function_exists('_') OR $locale_res===FALSE OR empty($locale_res) )
    {
        /**
         * gettext override
         * v 1.0.11
         * https://launchpad.net/php-gettext/
         * We load php-gettext here since Kohana_I18n tries to create the function __() function when we extend it.
         * PHP-gettext already does this.
         */
        include APPPATH.'vendor/php-gettext/gettext.inc';
        
        T_setlocale(LC_MESSAGES, $locale);
        T_bindtextdomain($domain,DOCROOT.'languages');
        T_bind_textdomain_codeset($domain, $charset);
        T_textdomain($domain);
    }
    /**
     * gettext exists using fallback in case locale doesn't exists
     */
    else
    {
        bindtextdomain($domain,DOCROOT.'languages');
        bind_textdomain_codeset($domain, $charset);
        textdomain($domain);

        function __($msgid)
        {
            return _($msgid);
        }
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


/**
 * gets the offset of a date
 * @param  string $offset 
 * @return string       
 */
function formatOffset($offset) 
{
        $hours = $offset / 3600;
        $remainder = $offset % 3600;
        $sign = $hours > 0 ? '+' : '-';
        $hour = (int) abs($hours);
        $minutes = (int) abs($remainder / 60);

        if ($hour == 0 AND $minutes == 0) {
            $sign = ' ';
        }
        return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) .':'. str_pad($minutes,2, '0');
}


/**
 * returns timezones ins a more friendly array way, ex Madrid [+1:00]
 * @return array 
 */
function get_timezones()
{
    if (method_exists('DateTimeZone','listIdentifiers'))
    {
        $utc = new DateTimeZone('UTC');
        $dt  = new DateTime('now', $utc);

        $timezones = array();
        $timezone_identifiers = DateTimeZone::listIdentifiers();

        foreach( $timezone_identifiers as $value )
        {
            $current_tz = new DateTimeZone($value);
            $offset     =  $current_tz->getOffset($dt);

            if ( preg_match( '/^(America|Antartica|Africa|Arctic|Asia|Atlantic|Australia|Europe|Indian|Pacific)\//', $value ) )
            {
                $ex=explode('/',$value);//obtain continent,city
                $city = isset($ex[2])? $ex[1].' - '.$ex[2]:$ex[1];//in case a timezone has more than one
                $timezones[$ex[0]][$value] = $city.' ['.formatOffset($offset).']';
            }
        }
        return $timezones;
    }
    else//old php version
    {
        return FALSE;
    }
}

/**
 * return HTML select for the timezones
 * @param  string $select_name 
 * @param  string $selected    
 * @return string              
 */
function get_select_timezones($select_name='TIMEZONE',$selected=NULL)
{
    if ($selected=='UTC') $selected='Europe/London';
    $timezones = get_timezones();
    $sel.='<select id="'.$select_name.'" name="'.$select_name.'">';
    foreach( $timezones as $continent=>$timezone )
    {
        $sel.= '<optgroup label="'.$continent.'">';
        foreach( $timezone as $city=>$cityname )
        {            
            $seloption = ($city==$selected) ? ' selected="selected"' : '';
            $sel .= "<option value=\"$city\"$seloption>$cityname</option>";
        }
        $sel.= '</optgroup>';
    }
    $sel.='</select>';

    return $sel;
}


/**
 * short cut to get $_POST
 * @param  string $name    index
 * @param  mixed $default default value to use if none is set
 * @return mixed          value form $_POST
 */
function cP($name,$default = NULL)
{
    return (isset($_POST[$name])? $_POST[$name]:$default);
}


/**
 * replaces in a file 
 * @param  string $orig_file 
 * @param  array $search   
 * @param  array $replace  
 * @return bool           
 */
function replace_file($orig_file,$search, $replace,$to_file = NULL)
{
    if ($to_file === NULL)
        $to_file = $orig_file;

    //check file is writable
    if (is_writable($to_file))
    {
        //read file content
        $content = file_get_contents($orig_file);
        //replace fields
        $content = str_replace($search, $replace, $content);
        //save file
        return write_file($to_file,$content);
    }
    
    return FALSE;
}


/**
 * write to file
 * @param $filename fullpath file name
 * @param $content
 * @return boolean
 */
function write_file($filename,$content)
{
    $file = fopen($filename, 'w');
    if ($file)
    {//able to create the file
        fwrite($file, $content);
        fclose($file);
        return TRUE;
    }
    return FALSE;   
}

/**
 * generates passwords, used for the auth hash keys etc..
 * @param  integer $length 
 * @return string         
 */
function generate_password ($length = 16)
{
    $password = '';
    // define possible characters
    $possible = '0123456789abcdefghijklmnopqrstuvwxyz_-';

    // add random characters to $password until $length is reached
    for ($i=0; $i <$length ; $i++) 
    { 
        // pick a random character from the possible ones
        $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
        $password .= $char;
    }

    return $password;
}
