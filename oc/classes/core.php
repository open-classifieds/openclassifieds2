<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Core class for OC, contains commons functions and helpers
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Core {
	
	/**
	 * 
	 * OC version
	 * @var string
	 */
	const version = '2.1.1';

	
	/**
	 * 
	 * Initializes configs for the APP to run
	 */
	public static function initialize()
	{	
        		
		/**
		 * Load all the configs from DB
		 */
		//Change the default cache system, based on your config /config/cache.php
		Cache::$default = Core::config('cache.default');
		
		//is not loaded yet in Kohana::$config
		Kohana::$config->attach(new ConfigDB(), FALSE);

		//overwrite default Kohana init configs.
		Kohana::$base_url = Core::config('general.base_url');
		
		//enables friendly url @todo from config
		Kohana::$index_file = FALSE;
		//cookie salt for the app
		Cookie::$salt = Core::config('auth.cookie_salt');
		
		// -- i18n Configuration and initialization -----------------------------------------
		I18n::initialize(Core::config('i18n.locale'),Core::config('i18n.charset'));

		//Loading the OC Routes
		// if (($init_routes = Kohana::find_file('config','routes')))
		// 	require_once $init_routes[0];//returns array of files but we need only 1 file
        //faster loading
        require_once APPPATH.'config/routes.php';

        //getting the selected theme, and loading options
        Theme::initialize();

	}
	

	/**
     * Shortcut to load a group of configs
     * @param type $group
     * @return array 
     */
    public static function config($group)
    {
    	return Kohana::$config->load($group);
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
    	return (Request::current()->post($key)!==NULL)?Request::current()->post($key):$default;
    }

    /**
     * shortcut to get or post
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function request($key,$default=NULL)
    {
        return (Core::post($key)!==NULL)?Core::post($key):Core::get($key,$default);
    }

    /**
     * shortcut for the cache instance
     * 
     * @param   string  $name       name of the cache
     * @param   mixed   $data       data to cache
     * @param   integer $lifetime   number of seconds the cache is valid for, if 0 delete cache
     * @return  mixed    for getting
     * @return  boolean  for setting
     */
    public static function cache($name, $data = NULL, $lifetime = NULL)
    {
        //deletes the cache
        if ($lifetime===0)
            return Cache::instance()->delete($name);

        //in development we do not store or read we always return null
        if (Kohana::$environment == Kohana::DEVELOPMENT)
            return NULL;

        //no data provided we read
        if ($data===NULL)
            return Cache::instance()->get($name);
        //saves data
        else
            return Cache::instance()->set($name,$data, $lifetime);
    }

    


    /**
     * Function modified from WordPress = http://phpdoc.wordpress.org/trunk/WordPress/_wp-includes---functions.php.html#functionget_file_data
     * 
     * Retrieve metadata from a file.
     *
     * Searches for metadata in the first 8kiB of a file, such as a plugin or theme.
     * Each piece of metadata must be on its own line. Fields can not span multiple
     * lines, the value will get cut at the end of the first line.
     *
     * If the file data is not within that first 8kiB, then the author should correct
     * their plugin file and move the data headers to the top.
     *     
     * 
     * @param string $file Path to the file
     * @param array $default_headers List of headers, in the format array('HeaderKey' => 'Header Name')
     * @return array
     */
    public static function get_file_data( $file, $default_headers) 
    {
        // We don't need to write to the file, so just open for reading.
        $fp = fopen( $file, 'r' );

        // Pull only the first 8kiB of the file in.
        $file_data = fread( $fp, 8192 );

        // PHP will close file handle, but we are good citizens.
        fclose( $fp );

        // Make sure we catch CR-only line endings.
        $file_data = str_replace( "\r", "\n", $file_data );

        foreach ( $default_headers as $field => $regex )
        {
            if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] )
                $default_headers[ $field ] = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '',  $match[1] ));
            else
                $default_headers[ $field ] = '';
        }

        return $default_headers;
    }



    /**
     * get updates from json hosted at our site
     * @param  boolean $reload  
     * @return void
     */
    public static function get_updates($reload = FALSE)
    {
        //we check the date of our local versions.php
        $version_file = APPPATH.'config/versions.php';
        
        //if older than a month or ?reload=1 force reload
        if ( time() > strtotime('+1 week',filemtime($version_file)) OR $reload === TRUE )
        {
            //read from oc/versions.json on CDN
            $json = Core::curl_get_contents('http://open-classifieds.com/files/versions.json?r='.time());
            $versions = json_decode($json,TRUE);
            if (is_array($versions))
            {
                //update our local versions.php
                $content = "<?php defined('SYSPATH') or die('No direct script access.');
                return ".var_export($versions,TRUE).";";// die($content);
                //@todo check file permissions?
                File::write($version_file, $content);
            }
            
        }
    }


    /**
     * get market from json hosted currently at our site
     * @param  boolean $reload  
     * @return void
     */
    public static function get_market($reload = FALSE)
    {
        $market_url = (Kohana::$environment!== Kohana::DEVELOPMENT)? 'market.open-classifieds.com':'eshop.lo';
        $market_url = 'http://'.$market_url.'/api/products';

        //try to get the json from the cache
        $market = Core::cache($market_url);

        //not cached :(
        if ($market === NULL OR  $reload === TRUE)
        {
            $market = Core::curl_get_contents($market_url.'?r='.time());
            //save the json
            Core::cache($market_url,$market,strtotime('+1 day'));
        }

        return json_decode($market,TRUE);

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
     * Akismet spam check. Invokes akismet class to get response is spam.
     * @param name
     * @param email
     * @param comment
     * @return bool
     */
    public static function akismet($name,$email,$comment)
    {
        require_once Kohana::find_file('vendor', 'akismet/akismet','php');

        if (core::config('general.akismet_key')!='')
        {
            $akismet = new Akismet(core::config('general.base_url') ,core::config('general.akismet_key'));
            $akismet->setCommentAuthor($name);
            $akismet->setCommentAuthorEmail($email);
            $akismet->setCommentContent($comment);
            return $akismet->isCommentSpam();
        }
        else //we return is not spam since we do not have the api :(
            return FALSE;
    }


} //end core

/**
 * Common functions
 */


/**
 *
 * Dies and var_dumps
 * @param any $var
 */
function d($var)
{
	die(var_dump($var));
}