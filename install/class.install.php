<?
/**
 * Helper installation classses
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

// Sanity check, install should only be checked from index.php
defined('SYSPATH') or exit('Install must be loaded from within index.php!');


/**
 * Class with install functions helper
 */
class install{
    
    /**
     * 
     * Software install settings
     * @var string
     */
    const VERSION   = '2.6.1';

    /**
     * default locale/language of the install
     * @var string
     */
    public static $locale = 'en_UK';

    /**
     * suggested URL with folder were to install
     * @var string
     */
    public static $url = NULL;

    /**
     * suggested folder were to install
     * @var string
     */
    public static $folder = NULL;

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
      * used to hash the password and set in the  
      * @var string
      */
    public static $hash_key  = '';

    /**
     * initializes the install class and process
     * @return void
     */
    public static function initialize()
    {
        //Gets language to use in the install
        self::$locale  = core::request('LANGUAGE', core::get_browser_favorite_language());

        //start translations
        install::gettext_init(self::$locale);

        // Try to guess installation URL
        self::$url = 'http://'.$_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_PORT'] != '80') 
            self::$url = self::$url.':'.$_SERVER['SERVER_PORT'];

        //getting the folder, erasing the index
        self::$folder = str_replace('/index.php','', $_SERVER['SCRIPT_NAME']).'/';
        self::$url .=self::$folder;
    }

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
                'Write DIR'       =>array('message'   => 'Can\'t write to the current directory. Please fix this by giving the webserver user write access to the directory.',
                                        'mandatory' => TRUE,
                                        'result'    => (is_writable(DOCROOT))
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
                                    'result'    => (is_dir(SYSPATH) AND is_file(SYSPATH.'classes/Kohana'.EXT))
                                    ),
                'APP'       =>array('message'   => 'The configured <code>'.APPPATH.'</code> directory does not exist or does not contain required files.',
                                    'mandatory' => TRUE,
                                    'result'    => (is_dir(APPPATH) AND is_file(APPPATH.'bootstrap'.EXT))
                                    ),
                'PHP'   =>array('message'   => 'PHP 5.5 or newer required, this version is '. PHP_VERSION,
                                    'mandatory' => TRUE,
                                    'result'    => version_compare(PHP_VERSION, '5.5', '>=')
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
        self::$msg = '';
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
     * includes a view file
     */
    public static function view($file)
    {
        include_once 'views/'.$file.EXT;
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
     * loads gettexts or droppin
     * @param  string $locale  
     * @param  string $domain  
     * @param  string $charset 
     */
    private static function gettext_init($locale,$domain = 'messages',$charset = 'utf8')
    {
        /**
         * check if gettext exists if not uses gettext dropin
         */
        $locale_res = setlocale(LC_ALL, $locale);
        if ( !function_exists('_') OR $locale_res===FALSE OR empty($locale_res) )
        {
            /**
             * gettext override
             * v 1.0.11
             * https://launchpad.net/php-gettext/
             * We load php-gettext here since Kohana_I18n tries to create the function __() function when we extend it.
             * PHP-gettext already does this.
             */
            include APPPATH.'common/vendor/php-gettext/gettext.inc';
            
            T_setlocale(LC_ALL, $locale);
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
     * replaces in a file 
     * @param  string $orig_file 
     * @param  array $search   
     * @param  array $replace  
     * @return bool           
     */
    public static function replace_file($orig_file,$search, $replace,$to_file = NULL)
    {
        if ($to_file === NULL)
            $to_file = $orig_file;

        //check file is writable
        // if (is_writable($to_file))
        // {
            //read file content
            $content = file_get_contents($orig_file);
            //replace fields
            $content = str_replace($search, $replace, $content);
            //save file
            return core::write_file($to_file,$content);
        // }
        // return FALSE;
    }

    /**
     * installs the software
     * @return [type] [description]
     */
    public static function execute()
    {
        $error_msg = '';
        $install    = TRUE;
    
        ///////////////////////////////////////////////////////
        //check DB connection
        $link = @mysqli_connect(core::request('DB_HOST'), core::request('DB_USER'), core::request('DB_PASS'));
        if (!$link) 
        {
            $error_msg = __('Cannot connect to server').' '. core::request('DB_HOST');
            $install = FALSE;
        }
        
        if ($link AND $install === TRUE) 
        {
            if (core::request('DB_NAME'))
            {
                //they selected to create the DB
                if (core::request('DB_CREATE'))
                    @mysqli_query($link,"CREATE DATABASE IF NOT EXISTS `".core::request('DB_NAME')."`");

                $dbcheck = @mysqli_select_db($link,core::request('DB_NAME'));
                if (!$dbcheck)
                {
                    $error_msg.= __('Database name').': ' . mysqli_error($link);
                    $install = FALSE;
                }
            }
            else 
            {
                $error_msg.= '<p>'.__('No database name was given').'. '.__('Available databases').':</p>';
                $db_list = @mysqli_query($link,'SHOW DATABASES');
                $error_msg.= '<pre>';
                if (!$db_list) 
                {
                    $error_msg.= __('Invalid query'). ':<br>' . mysqli_error($link);
                }
                else 
                {
                    while ($row = mysqli_fetch_row($db_list)) 
                    {
                        $error_msg.= $row['Database'] . '<br>';
                    }
                }

                $error_msg.= '</pre>';
                $install    = FALSE;
            }
        }

        //clean prefix
        $TABLE_PREFIX = core::slug(core::request('TABLE_PREFIX'));

        //save DB config/database.php
        if ($install === TRUE)
        {
            $_POST['TABLE_PREFIX'] = $TABLE_PREFIX;
            $_GET['TABLE_PREFIX'] = $TABLE_PREFIX;
            $search  = array('[DB_HOST]', '[DB_USER]','[DB_PASS]','[DB_NAME]','[TABLE_PREFIX]','[DB_CHARSET]');
            $replace = array(core::request('DB_HOST'), core::request('DB_USER'), core::request('DB_PASS'),core::request('DB_NAME'),$TABLE_PREFIX,core::request('DB_CHARSET'));
            $install = install::replace_file(INSTALLROOT.'samples/database.php',$search,$replace,APPPATH.'config/database.php');
            if (!$install === TRUE)
                $error_msg = __('Problem saving '.APPPATH.'config/database.php');
        }

        
        //install DB
        if ($install === TRUE)
        {
            //check if has key is posted if not generate
            self::$hash_key = ((core::request('HASH_KEY')!='')?core::request('HASH_KEY'): core::generate_password() );
           
            //check if DB was already installed, I use content since is the last table to be created
            $installed = (mysqli_num_rows(mysqli_query($link,"SHOW TABLES LIKE '".$TABLE_PREFIX."content'"))==1) ? TRUE:FALSE;

            if ($installed===FALSE)//if was installed do not launch the SQL. 
                include INSTALLROOT.'samples/install.sql'.EXT;
        }

        ///////////////////////////////////////////////////////
        //AUTH config
        if ($install === TRUE)
        {
            $search  = array('[HASH_KEY]', '[COOKIE_SALT]','[QL_KEY]');
            $replace = array(self::$hash_key,self::$hash_key,self::$hash_key);
            $install = install::replace_file(INSTALLROOT.'samples/auth.php',$search,$replace,APPPATH.'config/auth.php');
            if (!$install === TRUE)
                $error_msg = __('Problem saving '.APPPATH.'config/auth.php');
        }

        ///////////////////////////////////////////////////////
        //create robots.txt
        if ($install === TRUE)
        {
            $search  = array('[SITE_URL]', '[SITE_FOLDER]');
            $replace = array(core::request('SITE_URL'), core::request('SITE_FOLDER'));
            $install = install::replace_file(INSTALLROOT.'samples/robots.txt',$search,$replace,DOCROOT.'robots.txt');
            if (!$install === TRUE)
                $error_msg = __('Problem saving '.DOCROOT.'robots.txt');
        }


        ///////////////////////////////////////////////////////
        //create htaccess
        if ($install === TRUE)
        {
            $search  = array('[SITE_FOLDER]');
            $replace = array(core::request('SITE_FOLDER'));

            $install = install::replace_file(INSTALLROOT.'samples/example.htaccess',$search,$replace,DOCROOT.'.htaccess');

            if (!$install === TRUE)
                $error_msg = __('Problem saving '.DOCROOT.'.htaccess');
        }
        
        ///////////////////////////////////////////////////////
        //all good! 
        if ($install === TRUE) 
        {
            core::delete(INSTALLROOT.'install.lock');
            //core::delete(INSTALLROOT);//prevents from performing a new install
        }
        //not succeded :( delete all the tables with that prefix
        elseif($link!=FALSE)
        {
            $table_list = @mysqli_query($link,"SHOW TABLES LIKE '".$TABLE_PREFIX."%'");
            if ($table_list !== FALSE)
                while ($row = mysqli_fetch_assoc($table_list))
                    @mysqli_query($link,"DROP TABLE ".$row[0]);
        }
        

        self::$error_msg = $error_msg;
        return $install;
    }
}


class core{

    /**
     * generates passwords, used for the auth hash keys etc..
     * @param  integer $length 
     * @return string         
     */
    public static function generate_password ($length = 16)
    {
        // define possible characters
        $possible = '23456789+@%$*abcdefghjkmnpqrstuvwxyz';
        $possible_length = strlen($possible)-1;

        // add random characters to $password until $length is reached
        $password = '';
        for ($i=0; $i <$length ; $i++) 
        { 
            // pick a random character from the possible ones
            $password .= substr($possible, mt_rand(0, $possible_length), 1);
        }

        return $password;
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
     * cleans an string of spaces etc
     * @param  string $s 
     * @return string    clean
     */
    public static function slug($s) 
    {
        // everything to lower and no spaces begin or end
        $s = strip_tags(strtolower(trim($s)));
     
        // adding - for spaces and union characters
        $find = array(' ', '&', '+', '-',',','.',';');
        $s = str_replace ($find, '', $s);

        //return the friendly s
        return $s;
    }

    /**
     * write to file
     * @param $filename fullpath file name
     * @param $content
     * @return boolean
     */
    public static function write_file($filename,$content)
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
}
