<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/Kohana/Core'.EXT;
require COMMONPATH.'classes/kohana'.EXT;

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
//spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- To debug enable DEVELOPMENT environment by changing your localhost
if ( ! isset($_SERVER['SERVER_NAME']))
    Kohana::$environment = Kohana::STAGING;
elseif (OC_DEBUG OR $_SERVER['SERVER_NAME'] == 'reoc.lo')
    Kohana::$environment =  Kohana::DEVELOPMENT;
else
    Kohana::$environment = Kohana::PRODUCTION;


/**
 * Magic quotes enabled?
 */
if (function_exists('get_magic_quotes_gpc'))
{
    if (get_magic_quotes_gpc())
        Kohana::$magic_quotes = TRUE;
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */
Kohana::init(array(
    'base_url'  => '/',//later we change it taking it from the config
    'errors'    => TRUE,
    'profile'   => (Kohana::$environment === Kohana::DEVELOPMENT),
    'caching'   => (Kohana::$environment === Kohana::PRODUCTION),
));

/**
 * Define error levels = array of messages levels to write OR max level to write
 */
//Kohana::$log->attach(new Log_File(APPPATH.'logs'));
if ((Kohana::$environment !== Kohana::DEVELOPMENT) AND (Kohana::$environment !== Kohana::STAGING))
{
    //$LEVELS = array();
    $LEVELS = array(LOG_ERR);
}
else
{
    $LEVELS = array(LOG_INFO,LOG_ERR,LOG_DEBUG);
}
/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'),$LEVELS);

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
$modules = array(
	'themes'	    => DOCROOT.'themes',     // loaded as a module so we can search file using kohana find_file
    //KO Modules
	'auth'		    => KOMODPATH.'auth',       // Basic authentication
	'cache'		    => KOMODPATH.'cache',      // Caching with multiple backends
	'database'	    => KOMODPATH.'database',   // Database access
	'image'		    => KOMODPATH.'image',      // Image manipulation
	'orm'		    => KOMODPATH.'orm',        // Object Relationship Mapping
    //modules not included on the KO package but in the common module
    'common'        => COMMONPATH, // common classes open classifieds project
	'pagination'	=> COMMONPATH.'modules/pagination', // ORM Pagination
	'breadcrumbs'	=> COMMONPATH.'modules/breadcrumbs',// breadcrumb view
	'formmanager'	=> COMMONPATH.'modules/formmanager',// forms to objects ORM
	'widgets'	    => COMMONPATH.'modules/widgets',    // loads default widgets
    'cron'          => COMMONPATH.'modules/cron',    // cron module
    //modules unique for open classifieds
	'blacksmith'	=> MODPATH.'blacksmith', // used to handle custom fields
);

//modules for development environment, not included in distribution KO with OC, so you need to place them in your environment
//also we did a cleaning in KO removing all the tests and documentation to make it lighter
// if (Kohana::$environment == Kohana::DEVELOPMENT)
// {
//     @FIXME @TOFIX possible integration w/ PHPUnit ?
//     $modules['unittest'] =  MODPATH.'unittest';   // Unit testing
//     //$modules['userguide'] = MODPATH.'userguide';  // User guide and API documentation
// }

Kohana::modules($modules);
unset($modules);

// initializing the OC APP, and routes
Core::initialize();
