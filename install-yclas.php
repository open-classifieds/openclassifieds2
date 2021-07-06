<?php
/**
 * HTML template for the install
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@yclas.com>
 * @copyright  (c) 2009-2020 Yclas Inc Team
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
    die('<strong><u>Yclas Installation requirement</u></strong>: Before you proceed with your Yclas installation: Keep in mind Yclas uses the short tag "short cut" syntax.<br><br> Thus the <a href="http://php.net/manual/ini.core.php#ini.short-open-tag" target="_blank">short_open_tag</a> directive must be enabled in your php.ini.<br><br><u>Easy Solution</u>:<ol><li>Open php.ini file and look for line short_open_tag = Off</li><li>Replace it with short_open_tag = On</li><li>Restart then your PHP server</li><li>Refresh this page to resume your Yclas installation</li><li>Enjoy Yclas ;)</li></ol>');

//prevent from new install to be done over current existing one
if (file_exists(DOCROOT.'oc/config/database.php'))
    die('It seems Yclas is already installed');


//read from oc/versions.json on CDN
$versions       = install::versions();
$last_version   = key($versions);
$is_compatible  = install::is_compatible();


/**
 * Helper installation classes
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2016 Open Classifieds Team
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
    const VERSION   = '4.3.0';

    /**
     * default locale/language of the install
     * @var string
     */
    public static $locale = 'en_UK';

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
        $mod_result = ((function_exists('apache_get_modules') AND in_array('mod_rewrite',apache_get_modules()))
            OR (getenv('HTTP_MOD_REWRITE')=='On')
            OR (strpos(@shell_exec('/usr/local/apache/bin/apachectl -l'), 'mod_rewrite') !== FALSE)
            OR (isset($_SERVER['IIS_UrlRewriteModule'])));
        $mod_msg = ($mod_result)?NULL:'Can not check if mod_rewrite is installed, probably everything is fine. Try to proceed with the installation anyway ;)';


        /**
         * all the install checks
         */
        return     array(
                'New Installation'=>array('message'   => 'It seems that Yclas is already installed',
                                        'mandatory' => TRUE,
                                        'result'    => !file_exists('oc/config/database.php')
                                        ),
                'Write DIR'       =>array('message'   => 'Can\'t write to the current directory. Please fix this by giving the webserver user write access to the directory.',
                                        'mandatory' => TRUE,
                                        'result'    => (is_writable(DOCROOT))
                                        ),
                'PHP'   =>array('message'   => 'PHP 7.1 or newer is required, this version is '. PHP_VERSION,
                                    'mandatory' => TRUE,
                                    'result'    => version_compare(PHP_VERSION, '7.1', '>=')
                                    ),
                'mod_rewrite'=>array('message'  => $mod_msg,
                                    'mandatory' => FALSE,
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
                'Imagick'   =>array('message'   => 'Install requires the <a href="https://www.php.net/manual/en/book.imagick.php">Imagick</a> class',
                                    'mandatory' => TRUE,
                                    'result'    => (class_exists('Imagick'))
                                    ),
                'MySQL'     =>array('message'   => 'Install requires the <a href="http://php.net/mysqli">MySQLi</a> extension to support MySQL databases.',
                                    'mandatory' => TRUE,
                                    'result'    => (function_exists('mysqli_connect'))
                                    ),
                'ZipArchive'   =>array('message'   => 'PHP module zip not installed. You will need this to auto update the software.',
                                    'mandatory' => FALSE,
                                    'result'    => class_exists('ZipArchive')
                                    ),
                'SoapClient'   =>array('message'   => 'Install requires the <a href="http://php.net/manual/en/class.soapclient.php">SoapClient</a> class.',
                                    'mandatory' => FALSE,
                                    'result'    => class_exists('SoapClient')
                                    ),
                );
    }

    /**
     * checks from requirements if its compatible or not. Also fills the msg variable
     * @return boolean
     */
    public static function is_compatible()
    {
        self::$msg = [];
        $compatible = TRUE;
        foreach (install::requirements() as $name => $values)
        {
            if ($values['mandatory'] == TRUE AND $values['result'] == FALSE)
                $compatible = FALSE;

            if ($values['result'] == FALSE)
                self::$msg[] = $values['message'];
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
        return json_decode(core::curl_get_contents('https://raw.githubusercontent.com/yclas/yclas/master/versions.json?t='.time()),TRUE);
    }

    /**
     * return HTML select for the timezones
     * @param  string $select_name
     * @param  string $selected
     * @return string
     */
    public static function get_select_timezones($select_name='TIMEZONE', $selected=NULL)
    {
        if ($selected=='UTC')
            $selected='Europe/London';

        $timezones = core::get_timezones();
        $sel = '<select class="form-control" id="'.$select_name.'" name="'.$select_name.'">';
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
     * returns array of Yclas languages available
     * @TODO better get them by an ajax request
     *
     * @return array
     */
    public static function get_languages()
    {
        $locales = ['ar',
                    'bg_BG',
                    'bn_BD',
                    'ca_ES',
                    'da_DK',
                    'de_DE',
                    'el_GR',
                    'en_UK',
                    'en_US',
                    'es_ES',
                    'fr_FR',
                    'hi_IN',
                    'hu_HU',
                    'in_ID',
                    'it_IT',
                    'ja_JP',
                    'ml_IN',
                    'nl_NL',
                    'no_NO',
                    'pl_PL',
                    'pt_PT',
                    'ro_RO',
                    'ru_RU',
                    'sk_SK',
                    'sn_ZW',
                    'sq_AL',
                    'sr_RS',
                    'sv_SE',
                    'ta_IN',
                    'tl_PH',
                    'tr',
                    'ur_PK',
                    'vi_VN',
                    'zh_CN'
        ];

        return $locales;
    }

    /**
     * returns suggested installation url
     * @return string
     */
    public static function get_url()
    {
        // Try to guess installation URL
        // Check whether we are using HTTPS or not
        if (isset($_SERVER['HTTPS']))
        {
            if ('on' == strtolower($_SERVER['HTTPS']) OR '1' == $_SERVER['HTTPS'])
            {
                $url = 'https://'.$_SERVER['SERVER_NAME'];
            }
        }
        elseif (isset($_SERVER['SERVER_PORT']) AND ('443' == $_SERVER['SERVER_PORT']))
        {
            $url = 'https://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
        }
        else
        {
            $url = 'http://'.$_SERVER['SERVER_NAME'];

            if ($_SERVER['SERVER_PORT'] != '80')
                $url = $url.':'.$_SERVER['SERVER_PORT'];
        }

        $url .= self::get_folder();

        return $url;
    }

    /**
     * returns suggested installation folder
     * @return string
     */
    public static function get_folder()
    {
        // Getting the folder, erasing the install-openclassifieds
        $folder = str_replace('/install-yclas.php','', $_SERVER['SCRIPT_NAME']).'/';

        return $folder;
    }
}

class core{

    /**
     * copies files/directories recursively
     * @param  string  $source    from
     * @param  string  $dest      to
     * @param  integer $overwrite 0=do not overwrite 1=force overwrite 2=overwrite only is size is different
     * @return void
     */
    public static function copy($source, $dest, $overwrite = 0)
    {
        //be sure source exists..
        if (!is_readable($source))
            die('File ('.$source.') could not be readed, likely a permissions problem.');

        //just a file to copy, so do it!
        if(is_file($source))
        {
            $copy_file = FALSE;

            //if doesnt exists OR we want to overwrite always OR different size copy the file.
            if( !is_file( $dest ) OR $overwrite == 1 OR ( $overwrite == 2 AND filesize($source)===filesize($dest) ) )
                $copy_file = TRUE;

            if ($copy_file === TRUE)
            {
                try {
                    copy($source, $dest);
                } catch (Exception $e) {
                    die('File ('.$source.') could not be copied, likely a permissions problem.');
                }
            }

            //always return if its a file, so we dont move forward
            return;
        }

        //was not a file, so folder...lets check exists, if not create it
        if(!is_dir($dest))
            mkdir($dest);

        //read folder contents
        $objects = scandir($source);
        foreach ($objects as $object)
        {
            if($object != '.' && $object != '..')
            {
                $from = $source . '/' . $object;
                $to   = $dest   . '/' . $object;
                Core::copy($from, $to, $overwrite);
            }
        }

    }

    /**
     * recursively deletes file or directory
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
                        core::delete($file.'/'.$object); //recursive
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
     * @return string on success, false on errors
     */
    public static function curl_get_contents($url)
    {
        $c = curl_init(); if ($c === FALSE) return FALSE;
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($c, CURLOPT_TIMEOUT,30);
        curl_setopt($c, CURLOPT_NOPROGRESS, false);
        curl_setopt($c, CURLOPT_PROGRESSFUNCTION, array(new core, 'write_curl_progress'));
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
        $rss = @simplexml_load_file($url);
         if($rss == FALSE OR ! isset($rss->channel->item))
            return array();

        return $rss->channel->item;
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

    /**
     * write progress.json file of curl download progress
     * @param  string $resource
     * @param  string $download_size
     * @param  string $downloaded
     * @param  string $upload_size
     * @param  string $uploaded
     * @return void
     */
    public static function write_curl_progress($resource, $download_size, $downloaded, $upload_size, $uploaded )
    {
        static $previousProgress = 0;

        if ($download_size == 0)
            $progress = 0;
        else
            $progress = round($downloaded * 100 / $download_size);

        if ($progress > $previousProgress)
        {
            $previousProgress = $progress;
            $fp = fopen('progress.json', 'wa+');
            fputs($fp, json_encode(array('progress' => array($progress))));
            fclose($fp);
        }
    }

    /**
     * gets the offset of a date
     * @param  string $offset
     * @return string
     */
    public static function format_offset($offset)
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
    public static function get_timezones()
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
                    $timezones[$ex[0]][$value] = $city.' ['.core::format_offset($offset).']';
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
     * Parse Accept-Language HTTP header to detect user's language(s)
     * and get the most favorite one
     *
     * Adapted from
     * @link http://www.thefutureoftheweb.com/blog/use-accept-language-header
     * @param string $lang default language to return in case of any
     * @return NULL|string  favorite user's language
     *
     */
    public static function get_browser_favorite_language($lang = 'en_US')
    {
        if ( ! isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            return $lang;

        // break up string into pieces (languages and q factors)
        preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

        if ( ! count($lang_parse[1]))
            return $lang;

        // create a list of languages in the form 'es' => 0.8
        $langs = array_combine($lang_parse[1], $lang_parse[4]);

        // set default to 1 for languages without a specified q factor
        foreach ($langs as $lang => $q)
            if ($q === '') $langs[$lang] = 1;

        arsort($langs, SORT_NUMERIC); // sort list based on q factor. higher first
        reset($langs);
        $lang = strtolower(key($langs)); // Gotcha ! the 1st top favorite language

        // when necessary, convert 'es' to 'es_ES'
        // so scandir("languages") will match and gettext_init below can seamlessly load its stuff
        if (strlen($lang) == 2)
            $lang .= '_'.strtoupper($lang);

        return str_replace('-', '_', $lang);
    }
}

/**
 * gettext short cut currently @TODO just echoes untranslated string
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

    <title>Yclas Self-Hosted <?=__("Installation")?></title>
    <meta name="copyright" content="Yclas Self-Hosted <?=install::VERSION?>" >
    <meta name="author" content="Yclas Self-Hosted">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="shortcut icon" href="https://yclas.com/images/favicon.ico?v0.1" />

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>    <![endif]-->

    <link href="//cdn.jsdelivr.net/npm/bootstrap@3.4.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/fontawesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/animatecss/3.3.0/animate.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/css/selectize.bootstrap3.min.css" rel="stylesheet">

    <style type="text/css">
        body {
            background-color: #f3f3f4;
            padding-top: 80px;
            padding-bottom: 15px;
            color: #676a6c;
        }
        a {
            color: #0072A6;
        }
        a:hover {
            color: #00587F;
        }
        .logo {
            height: 45px;
        }
        .off-canvas {
            background-color: #ffffff;
            padding: 5px 20px 20px;
            -webkit-border-radius: 4px;
               -moz-border-radius: 4px;
                    border-radius: 4px;
            -webkit-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);
               -moz-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);
                    box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);
            -webkit-transition: max-height 0.8s;
               -moz-transition: max-height 0.8s;
                    transition: max-height 0.8s;
        }
        .off-canvas h3 {
            margin-top: 0;
            font-size: 13px;
            text-align: center;
            text-transform: uppercase;
            font-weight: 400;
            padding: 15px 0;
            letter-spacing: 2px;
            border-bottom: 1px solid #EDEDEE;
            margin-bottom: 15px;
        }
        .form-control {
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            border-color: #e5e6e7;
        }
        .selectize-input {
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            border-color: #e5e6e7;
        }
        .selectize-control.single .selectize-input::after {
            border-color: #676a6c transparent transparent;
        }
        .copyright {
            font-size: 12px;
        }
        .copyright a {
            color: #676a6c;
        }
        .btn-default {
            background-color: #41bb19;
            border-color: #41bb19;
            color: #FFFFFF;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .btn-default:hover,
        .btn-default:focus,
        .btn-default.focus,
        .btn-default:active,
        .btn-default.active,
        .open > .dropdown-toggle.btn-default {
            color: #FFFFFF;
            background-color: #32AF0A;
            border-color: #32AF0A;
        }
        .btn-default.disabled,
        .btn-default.disabled.active,
        .btn-default.disabled.focus,
        .btn-default.disabled:active,
        .btn-default.disabled:focus,
        .btn-default.disabled:hover,
        .btn-default[disabled],
        .btn-default.active[disabled],
        .btn-default.focus[disabled],
        .btn-default[disabled]:active,
        .btn-default[disabled]:focus,
        .btn-default[disabled]:hover {
            background-color: #32AF0A;
            border-color: #32AF0A;
        }
        .btn.active.focus,
        .btn.active:focus,
        .btn.focus,
        .btn:active.focus,
        .btn:active:focus,
        .btn:focus {
            outline: 0;
        }
        .text-success {
            color: #41bb19;
        }
        .btn-default.action {
            letter-spacing: 0px;
        }
        .list-requirements {
            margin-bottom: 10px;
            font-size: 12px;
            line-height: 25px;
            padding-bottom: 10px;
            -webkit-column-count: 2;
               -moz-column-count: 2;
                    column-count: 2;
            -webkit-column-gap: 15px;
               -moz-column-gap: 15px;
                    column-gap: 15px;
        }
        .list-requirements .check {
            vertical-align: 4%;
        }
        .list-requirements .check .fa-spinner {
            width: 20px;
        }
        .has-feedback label ~ .form-control-feedback.fa {
            top: 35px;
        }
        .has-success .checkbox,
        .has-success .checkbox-inline,
        .has-success .control-label,
        .has-success .help-block,
        .has-success .radio,
        .has-success .radio-inline,
        .has-success.checkbox label,
        .has-success.checkbox-inline label,
        .has-success.radio label,
        .has-success.radio-inline label {
            color: #41bb19;
        }
        .has-success .form-control {
            border-color: #41bb19;
        }
        .has-success .form-control-feedback {
            color: #41bb19;
        }
        .form-control:focus,
        .has-success .form-control:focus,
        .selectize-input.focus {
            border-color: #32AF0A;
            -webkit-box-shadow: none;
               -moz-box-shadow: none;
                    box-shadow: none;
        }
        .has-error .form-control:focus{
            -webkit-box-shadow: none;
               -moz-box-shadow: none;
                    box-shadow: none;
        }
        .loading {
            background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
            background-size: 40px 40px;
            -webkit-animation: progress-bar-stripes 2s linear infinite;
                    animation: 2s linear 0s normal none infinite progress-bar-stripes;
        }
        .btn.btn-default.loading {
            background-image: linear-gradient(45deg, rgba(235, 235, 235, 0.15) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(235, 235, 235, 0.15) 50%, rgba(235, 235, 235, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
        }
        .alert {
            background-color: #FFFFFF;
            color: #676a6c;
            border-width: 0;
            -webkit-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);
               -moz-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);
                    box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);
        }
        .alert-success {
            background-color: rgba(39, 194, 76, 0.15);
            color: #676a6c;
        }
        .alert-info {
            background-color: rgba(35, 183, 229, 0.15);
            color: #676a6c;
        }
        .alert-warning {
            background-color: rgba(255, 144, 43, 0.15);
            color: #676a6c;
        }
        .alert-danger {
            background-color: rgba(240, 80, 80, 0.15);
            color: #676a6c;
        }
        .alert-services {
            background-color: #41BB19;
            color: #ffffff;
            padding-top: 7px;
            padding-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 12px;
        }
        .alert-services:hover {
            background-color: #32AF0A;
        }
        .alert-services a {
            color: #FFFFFF;
        }
        .alert-services a:hover {
            text-decoration: none;
        }
        .alerts {
            margin-top: -60px;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="row">
            <?
            //choosing what to display
            //execute installation since they are posting data
            if ( $_POST  AND $is_compatible === TRUE)
            {
                //theres post, download latest version, unzip and rediret to install
                //download file
                file_put_contents('progress.json', '');
                $file_content = core::curl_get_contents($versions[$last_version]['download']);
                file_put_contents('oc.zip', $file_content);

                $zip = new ZipArchive;
                // open zip file, extract to dir
                if ($zip_open = $zip->open('oc.zip'))
                {
                    if ( !($folder_update = $zip->getNameIndex(0)) )
                    {
                        hosting_view();
                        exit;
                    }

                    $zip->extractTo(DOCROOT);
                    $zip->close();

                    core::copy($folder_update, DOCROOT);

                    // delete downloaded zip file
                    core::delete($folder_update);
                    @unlink('oc.zip');
                    @unlink('progress.json');
                    @unlink($_SERVER['SCRIPT_FILENAME']);

                    // redirect to install
                    header("Location: index.php");
                }
                else
                    hosting_view();
            }
            //normally if compatible just display the form
            elseif ($is_compatible === TRUE)
            {?>
                <?if (!empty(install::$msg) OR !empty(install::$error_msg))
                        hosting_view();?>
            <?}
            //not compatible
            else
                hosting_view();
            ?>
            <div class="col-md-8 col-md-offset-2 animated fadeIn">
                <div class="row">
                    <div class="col-md-6">
                        <h2><a target="_blank" href="https://yclas.com/"><img class="logo" src="https://i0.wp.com/yclas.nyc3.digitaloceanspaces.com/images/yclas_Logo_noTagline_144x44.png"></a></h2>
                        <br>
                        <p><strong><?=__("Welcome to the super easy and fast installation")?></strong></p>
                        <p>Yclas.com is an open source powerful PHP classifieds script that can help you start a website and turn it into a fully customizable classifieds site within a few minutes.</p>
                        <br>
                        <p class="text-center"><strong><?=__('Can’t get it to work?')?></strong></p>
                        <p><a target="_blank" href="https://selfhosted.yclas.com/" class="btn btn-default btn-large btn-block"><?=__("Get our professional services")?></a></p>
                    </div>
                    <div class="col-md-6">
                        <div class="off-canvas animated">
                            <form method="post" action="index.php">
                                <div class="panel-1">
                                    <h3><?=__("Software Requirements")?>  v.<?=$last_version;?></h3>
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
                                    <?if ($is_compatible === TRUE):?>
                                        <form method="post" action="">
                                            <p>
                                                <input type="hidden" name="dummy">
                                                <button class="btn btn-default btn-block download submit" type="button">Download and Install</button>
                                            </p>
                                            <div class="progress hidden">
                                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                            </div>
                                            <p>
                                                <small>
                                                    We will download Yclas Self-Hosted <?=$last_version?> and redirect you to the installation form.
                                                </small>
                                            </p>
                                        </form>
                                        <div class="panel-2">
                                            <h3><?=__('DB Configuration')?></h3>
                                            <div class="form-group">
                                                <label for="DB_HOST" class="control-label"><?=__("Host name")?></label>
                                                <input type="text" id="DB_HOST" name="DB_HOST" class="form-control" value="<?=core::request('DB_HOST','localhost')?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="DB_NAME" class="control-label"><?=__("Database name")?></label>
                                                <input type="text" id="DB_NAME" name="DB_NAME" class="form-control" value="<?=core::request('DB_NAME','openclassifieds')?>" required>
                                                <p class="help-block"><small><a target="_blank" href="https://docs.yclas.com/create-mysql-database/"><?=__("How to create a MySQL database?")?></a></small></p>
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
                                                <input type="text" id="TABLE_PREFIX" name="TABLE_PREFIX" class="form-control" value="<?=core::request('TABLE_PREFIX','yc4_')?>" required>
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
                                                <div class="form-group">
                                                    <label for="LANGUAGE" class="control-label"><?=__("Site Language")?></label>
                                                    <select id="LANGUAGE" name="LANGUAGE" class="form-control" required>
                                                        <?php
                                                            foreach (install::get_languages() as $language)
                                                            {
                                                                $selected = ( strtolower($language)==strtolower(core::request('LANGUAGE', install::$locale))) ? ' selected="selected"' : '';
                                                                echo "<option $selected value=\"$language\">$language</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group hidden animated site-adv">
                                                    <label for="SITE_URL" class="control-label"><?=__("Site URL");?>:</label>
                                                    <input type="text" id="SITE_URL" name="SITE_URL" class="form-control" size="75" value="<?=core::request('SITE_URL',install::get_url())?>" required>
                                                </div>
                                                <div class="form-group hidden animated site-adv">
                                                    <label form="SITE_FOLDER" class="control-label"><?=__("Installation Folder");?>:</label>
                                                    <input type="text" id="SITE_FOLDER" name="SITE_FOLDER" class="form-control" size="75" value="<?=core::request('SITE_FOLDER',install::get_folder())?>" required>
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
                                                <p><button type="button" class="btn btn-default btn-block install submit"><?=__('Install')?></button></p>
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
                                                            <a class="btn btn-default btn-block" href="<?=core::request('SITE_URL', install::get_url())?>"><?=__('Go to Your Website')?></a>
                                                        </p>
                                                        <p>
                                                            <a class="btn btn-default btn-block" href="<?=core::request('SITE_URL', install::get_url())?>oc-panel/">Admin</a>
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
                        <p>Copyright <a target="_blank" href="https://yclas.com/">Yclas.com</a></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-right">&copy; 2009-<?=date('Y')?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="//cdn.jsdelivr.net/jquery/1.12.3/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/bootstrap@3.4.0/dist/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.min.js"></script>
    <script src="//cdn.rawgit.com/scotch-io/scotch-panels/1.0.3/dist/scotchPanels.min.js"></script>

    <script>
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

        jQuery.fn.animateAuto = function(prop, speed, callback){
            var elem, height, width;
            return this.each(function(i, el){
                el = jQuery(el), elem = el.clone().css({"height":"auto","width":"auto"}).appendTo("body");
                height = elem.css("height"),
                width = elem.css("width"),
                elem.remove();

                if(prop === "height")
                    el.animate({"height":height}, speed, callback);
                else if(prop === "width")
                    el.animate({"width":width}, speed, callback);
                else if(prop === "both")
                    el.animate({"width":width,"height":height}, speed, callback);
            });
        }

        $(function(){
            var panelDB =   $('.panel-2').scotchPanel({
                                containerSelector: '.panel-1',
                                direction: 'right',
                                duration: 300,
                                transition: 'ease',
                                clickSelector: '.toggle-panel-1',
                                distanceX: '100%',
                                enableEscapeKey: false,
                                beforePanelOpen: function() {
                                    var height = $('.panel-2 .scotch-panel-wrapper:first').height();
                                    $('.panel-1 .scotch-panel-wrapper:first').animate({height:height}, 300);
                                },
                                beforePanelClose: function() {
                                    $('.panel-1 .scotch-panel-wrapper:first').animateAuto("height", 300);
                                },
                            });

            var panelSite =   $('.panel-3').scotchPanel({
                                containerSelector: '.panel-2',
                                direction: 'right',
                                duration: 300,
                                transition: 'ease',
                                clickSelector: '.toggle-panel-2',
                                distanceX: '100%',
                                enableEscapeKey: false,
                                beforePanelOpen: function() {
                                    var height = $('.panel-3 .scotch-panel-wrapper:first').height();
                                    $('.panel-2 .scotch-panel-wrapper:first').animate({height:height}, 300);
                                    $('.panel-1 .scotch-panel-wrapper:first').animate({height:height}, 300);
                                },
                                beforePanelClose: function() {
                                    $('.panel-2 .scotch-panel-wrapper:first').css({height:'auto'});
                                    var height = $('.panel-2 .scotch-panel-wrapper:first').height();
                                    $('.panel-1 .scotch-panel-wrapper:first').animate({height:height}, 300);
                                },
                            });

            var panelSuccess = $('.panel-4').scotchPanel({
                                containerSelector: '.panel-3',
                                direction: 'right',
                                duration: 300,
                                transition: 'ease',
                                distanceX: '100%',
                                enableEscapeKey: false,
                                beforePanelOpen: function() {
                                    var height = $('.panel-4 .scotch-panel-wrapper:first').height();
                                    $('.panel-3 .scotch-panel-wrapper:first').animate({height:height}, 300);
                                    $('.panel-2 .scotch-panel-wrapper:first').animate({height:height}, 300);
                                    $('.panel-1 .scotch-panel-wrapper:first').animate({height:height}, 300);
                                }
                            });

            $(".validate-db").click(function(event) {
                var validate = $(this).closest('form').validate();

                if (validate.element('#DB_HOST') === true
                    && validate.element('#DB_NAME') === true
                    && validate.element('#DB_USER') === true
                    && validate.element('#DB_PASS') === true
                    && validate.element('#DB_CHARSET') === true
                    && validate.element('#TABLE_PREFIX') === true){
                    panelSite.open();
                }
                else {
                    $('.off-canvas').addClass('shake');
                    $('.off-canvas').one('webkitAnimationEnd oanimationend msAnimationEnd animationend',
                        function () {
                            $('.off-canvas').removeClass('shake');
                    });
                }
            });

            $(".install.submit").click(function(event) {
                $form = $(this).closest('form');
                if ($form.valid()) {
                    var btn = $(this)
                    btn.addClass('disabled loading');
                    $.ajax({
                        url: 'index.php',
                        type: 'post',
                        dataType: 'json',
                        data: $form.serialize(),
                        success: function(data) {
                                    btn.removeClass('loading');
                                    $('p.form-control-static.admin_email').html(data.admin_email);
                                    $('p.form-control-static.admin_pwd').html(data.admin_pwd);
                                    panelSuccess.open();
                                },
                        error: function () {
                                    $form.submit();
                                }
                    });
                }
                else {
                    $('.off-canvas').addClass('shake');
                    $('.off-canvas').one('webkitAnimationEnd oanimationend msAnimationEnd animationend',
                        function () {
                            $('.off-canvas').removeClass('shake');
                    });
                }
            });

            if ($( ".alert-danger" ).length > 0) {
                panelDB.open();
            }

            $(".download.submit").click(function(event) {
                $btn = $(this);
                $form = $btn.closest('form');
                $(".progress").removeClass('hidden').addClass('active');
                $btn.attr('disabled', 'disabled').addClass('hidden');
                progress = setInterval(function(){
                                $.get( 'progress.json', function(data) {
                                    if (data.progress == 100)
                                    {
                                        clearInterval(progress);
                                        $(".progress > div").width(data.progress + '%').html('Uncompressing...');
                                        return;
                                    }

                                    $(".progress > div").width(data.progress + '%').html(data.progress + '%');
                                });
                            }, 1500);
                            $.ajax({
                                url: $(location).attr('href'),
                                type: 'post',
                                data: $form.serialize(),
                                success: function() {
                                    $(".progress").removeClass('active');
                                    panelDB.open();
                                }
                            });
            });
        });

        $("#show-adv-db").click(function() {
            $(this).hide();
            $(".db-adv").removeClass('hidden').addClass('fadeIn');
            var height = $('.panel-2 .scotch-panel-wrapper:first').height();
            $('.panel-1 .scotch-panel-wrapper:first').animate({height:height}, 300);
        });

        $("#show-adv-site").click(function() {
            $(this).hide();
            $(".site-adv").removeClass('hidden').addClass('fadeIn');
            var height = $('.panel-3 .scotch-panel-wrapper:first').height();
            $('.panel-2 .scotch-panel-wrapper:first').animate({height:height}, 300);
            $('.panel-1 .scotch-panel-wrapper:first').animate({height:height}, 300);
        });

        jQuery.validator.setDefaults({
            highlight: function (element, errorClass, validClass) {
                if (element.type === "radio") {
                    this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                } else {
                    $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
                    $(element).closest('.form-group').find('i.fa').remove();
                    $(element).closest('.form-group').append('<i class="fa fa-exclamation form-control-feedback"></i>');
                }
            },
            unhighlight: function (element, errorClass, validClass) {
                if (element.type === "radio") {
                    this.findByName(element.name).removeClass(errorClass).addClass(validClass);
                } else {
                    $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
                    $(element).closest('.form-group').find('i.fa').remove();
                    $(element).closest('.form-group').append('<i class="fa fa-check form-control-feedback"></i>');
                }
            },
            errorPlacement: function () {}
        });

        $('select').selectize();

        $('.list-requirements li').each(function(i){
            var l = $(this);
            var color = $(this).data('color');
            var result = $(this).data('result');
            setTimeout(function(){
                //l.show().addClass('animated fadeInRight');
                l.find('.check').html('<small><span class="animated bounceIn fa-stack text-' + color +'"><i class="fa fa-circle-o fa-stack-2x"></i><i class="fa fa-' + result + ' fa-stack-1x"></i></span></small>');
            }, (i+1) * 200);
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
    <div class="col-md-8 col-md-offset-2">
        <div class="row alerts">
            <div class="col-md-12">
                <?if (!empty(install::$error_msg)):?>
                    <div class="alert alert-danger animated fadeInDown">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <?=install::$error_msg?>
                    </div>
                <?endif?>

                <?if(!empty(install::$msg)):?>
                    <?if(install::is_compatible()):?>
                        <div class="alert alert-info animated fadeInDown">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?=__("We have detected some incompatibilities, installation may not work as expected but you can try.")?>
                        </div>
                    <?endif?>
                    <?foreach(install::$msg as $msg):?>
                        <div class="alert alert-warning animated fadeInDown">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?=$msg?>
                        </div>
                    <?endforeach?>
                <?endif?>

                <div class="alert animated fadeInDown">
                    <p class="text-danger"><strong>Your hosting seems to be not compatible. Check your settings.</strong></p>
                    <p>We have partnership with hosting companies to assure compatibility. And we include:</p>
                    <br>
                    <ul>
                        <li>100% Compatible High Speed Hosting</li>
                        <li>Get a huge discount on our Pro pack with all themes.</li>
                        <li>Professional Installation and Support.</li>
                    </ul>
                    <br>
                    <p>
                        <a class="btn btn-default btn-large" href="https://yclas.com/self-hosted.html#hosting">
                            <i class=" icon-shopping-cart icon-white"></i> Get Hosting! Less than $4 Month
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?
}
?>
