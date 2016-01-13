<?php defined('SYSPATH') or die('No direct access allowed.');

// -- Routes Configuration and initialization -----------------------------------------

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

/**
 * Reserved pages for OC usage. They use the i18n translation
 * We will use them with extension .htm to avoid repetitions with others.
 */

/**
 * Item / post new
 * URL::title(__('publish new'))
 */
Route::set('post_new', URL::title(__('publish new')).'.html')
->defaults(array(
		'controller' => 'new',    
		'action'     => 'index',
));

/**
 * search
 */
Route::set('search',URL::title(__('search')).'.html')
->defaults(array(
        'controller' => 'ad',    
        'action'     => 'advanced_search',
));

/**
 * Captcha / contact
 */
Route::set('contact', URL::title(__('contact')).'.html')
->defaults(array(
		'controller' => 'contact',
		'action'	 => 'index',));

/**
 * maps
 */
Route::set('map', URL::title(__('map')).'.html')
->defaults(array(
        'controller' => 'map',
        'action'     => 'index',));
/**
 * maintenance
 */
Route::set('maintenance', URL::title(__('maintenance')).'.html')
->defaults(array(
        'controller' => 'maintenance',
        'action'     => 'index',));

/**
 * page view public
 */
Route::set('page','<seotitle>.html')
->defaults(array(
        'controller' => 'page',    
        'action'     => 'view',
        'seotitle'	 => '',
));


/**
 * rss for blog
 */
Route::set('rss-blog','rss/blog.xml')
->defaults(array(
        'controller' => 'feed',    
        'action'     => 'blog',
));

/**
 * rss for forum
 */
Route::set('rss-forum','rss/forum(/<forum>).xml')
->defaults(array(
        'controller' => 'feed',    
        'action'     => 'forum',
));

/**
 * rss
 */
Route::set('rss','rss(/<category>(/<location>)).xml')
->defaults(array(
        'controller' => 'feed',    
        'action'     => 'index',
));

/**
 * site info json
 */
Route::set('sitejson','info.json')
->defaults(array(
        'controller' => 'feed',    
        'action'     => 'info',
));



//-------END reserved pages

/**
 * user admin/panel route
 */
Route::set('oc-panel', 'oc-panel(/<controller>(/<action>(/<id>)))')
->defaults(array(
		'directory'  => 'panel',
		'controller' => 'home',
		'action'     => 'index',
));

/**
 * blog
 */
Route::set('blog', 'blog(/<seotitle>.html)')
->defaults(array(
        'controller' => 'blog',    
        'action'     => 'index',
));

/**
 * FAQ
 */
Route::set('faq', 'faq(/<seotitle>.html)')
->defaults(array(
        'controller' => 'faq',    
        'action'     => 'index',
));

/**
 * forum new topic
 */
Route::set('forum-new', 'forum/'.URL::title(__('new topic')).'.html')
->defaults(array(
        'controller' => 'forum',    
        'action'     => 'new',
));

/**
 * forum topic
 */
Route::set('forum-topic', 'forum/<forum>/<seotitle>.html')
->defaults(array(
        'controller' => 'forum',    
        'action'     => 'topic',
));

/**
 * specific forum list of topics
 */
Route::set('forum-list', 'forum/<forum>')
->defaults(array(
        'controller' => 'forum',    
        'action'     => 'list',
));

/**
 * all forums / home page
 */
Route::set('forum-home', 'forum')
->defaults(array(
        'controller' => 'forum',    
        'action'     => 'index',
));


/**
 * ad reviews page
 */
Route::set('ad-review', URL::title(__('reviews')).'/<seotitle>.html')
->defaults(array(
        'controller' => 'ad',    
        'action'     => 'reviews',
));

/**
 * javascript localization
 */
Route::set('jslocalization', 'jslocalization/<action>')
->defaults(array(
        'controller' => 'jslocalization',    
        'action'     => 'validate',
));


/*
    user profile route 
 */
Route::set('profiles', 'user')
->defaults(array(
        'controller' => 'user',
        'action'     => 'index',
));

/*
    user profile route 
 */
Route::set('profile', 'user/<seoname>')
->defaults(array(
        'controller' => 'user',
        'action'     => 'profile',
));

/**
 * rss for user
 */
Route::set('rss-profile','user/<seoname>.xml')
->defaults(array(
        'controller' => 'feed',    
        'action'     => 'profile',
));


/**
 * Item / ad view (public)
 */
Route::set('ad', '<category>/<seotitle>.html')
->defaults(array(
		'controller' => 'ad',    
		'action'     => 'view',
));



/**
 * Sort by Category / Location
 */
Route::set('list', '<category>(/<location>)')
->defaults(array(
		'category'	 => URL::title(__('all')),
		'controller' => 'ad',    
		'action'     => 'listing',
));


/**
 * Error router
 */
Route::set('error', 'oc-error/<action>(/<message>)',
array('action' => '[0-9]++','message' => '.+'))
->defaults(array(
    'controller' => 'error',
    'action'     => 'index'
));


/**
 * API V1 REST route, if action is a numeric its swaped with ID
 */
Route::set('api', 'api/<version>/<controller>(/<action>(/<id>))(.<format>)',
    array(
        'version' => 'v1',
        'format'  => '(json|xml|csv|html)',
    ))
    ->defaults(array(
        'version'    => 'v1',
        'directory'  => 'api',
        'format'     => 'json',
        'action'     => 'index',
));


/**
 * Default route
 */

// changes the landing page of website, buy checking config.
// it reads config that is stored as a JSON
// if config returns null (doesn't exists), set default controller and action.

$landing = core::config('general.landing_page');
if($landing != NULL)
{
	$landing = json_decode($landing);
	$controller_home = $landing->controller;
	$action_home = $landing->action;
}
else
{
	$controller_home = 'home';
	$action_home = 'index';
}

Route::set('default', '(<controller>(/<action>(/<id>)))')
->defaults(array(
		'controller' =>  $controller_home,
		'action'     => $action_home,
));
