<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Front end controller for OC user/admin auth in the app
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Auth_Controller extends Controller
{

	/**
	 *
	 * Contruct that checks you are loged in before nothing else happens!
	 */
	function __construct(Request $request, Response $response)
	{
		// Assign the request to the controller
		$this->request = $request;

		// Assign a response to the controller
		$this->response = $response;


		//login control, don't do it for auth controller so we dont loop
		if ($this->request->controller()!='auth')
		{
			
			$url_bread = Route::url('oc-panel',array('controller'  => 'home'));
			Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Panel'))->set_url($url_bread));
				
			//check if user is login
			if (!Auth::instance()->logged_in( $request->controller(), $request->action(), $request->directory()))
			{
				Alert::set(Alert::ERROR, __('You do not have permissions to access '.$request->controller().' '.$request->action()));
				$url = Route::get('oc-panel')->uri(array(
													 'controller' => 'auth', 
													 'action'     => 'login'));
				$this->request->redirect($url);
			}

            //in case we are loading another theme since we use the allow query we force the configs of the selected theme
            if (Theme::$theme != Core::config('appearance.theme') AND Core::config('appearance.allow_query_theme')=='1') 
                Theme::initialize(Core::config('appearance.theme'));

		}

		//the user was loged in and with the right permissions
        parent::__construct($request,$response);
		
		
	}


	/**
	 * Initialize properties before running the controller methods (actions),
	 * so they are available to our action.
	 * @param  string $template view to use as template
	 * @return void           
	 */
	public function before($template = NULL)
	{
        Theme::checker();
        
        $this->maintenance();
	
		if($this->auto_render===TRUE)
		{
			// Load the template
			$this->template = ($template===NULL)?'oc-panel/main':$template;
			$this->template = View::factory($this->template);
				
			// Initialize empty values
			$this->template->title            = __('Panel').' - '.core::config('general.site_name');
			$this->template->meta_keywords    = '';
			$this->template->meta_description = '';
			$this->template->meta_copyright   = 'Open Classifieds '.Core::version;
			$this->template->header           = View::factory('oc-panel/header');
			$this->template->content          = '';
			$this->template->footer           = View::factory('oc-panel/footer');
			$this->template->styles           = array();
			$this->template->scripts          = array();
			$this->template->user 			  = Auth::instance()->get_user();


			/**
			 * custom options for the theme
			 * @var array
			 */
			Theme::$options = Theme::get_options();
			//we load earlier the theme since we need some info
			Theme::load();

			if (Theme::get('cdn_files') == FALSE)
			{
				//other color
	            if (Theme::get('admin_theme')!='bootstrap' AND Theme::get('admin_theme')!='')
	            {
	                $theme_css = array('css/'.Theme::get('admin_theme').'-bootstrap.min.css' => 'screen',);
	            }
	            //default theme
	            else
	            {
	                $theme_css = array('css/bootstrap.min.css' => 'screen');
	            }

            	$common_css = array('css/chosen.min.css' => 'screen',
            						'css/jquery.sceditor.min.css'=>'screen', 
                                    'css/admin-styles.css' => 'screen');

            	Theme::$styles = array_merge($theme_css,$common_css);

	            Theme::$scripts['footer'] = array('js/jquery-1.10.2.js',
	            								  'js/oc-panel/sidebar.js',	
												  'js/jquery.sceditor.min.js',
												  'js/bootstrap.min.js', 
											      'js/chosen.jquery.min.js',
                                                  'js/oc-panel/theme.init.js?v=2.1.6',
                                                  );
			}
			else
			{
	            //other color
	            if (Theme::get('admin_theme')!='bootstrap' AND Theme::get('admin_theme')!='')
	            {
	                $theme_css = array('http://netdna.bootstrapcdn.com/bootswatch/3.1.1/'.Theme::get('admin_theme').'/bootstrap.min.css' => 'screen',);
	            }
	            //default theme
	            else
	            {
	                $theme_css = array('http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css' => 'screen');
	            }

            	$common_css = array('http://cdn.jsdelivr.net/chosen/1.0.0/chosen.css' => 'screen', 
                                    'http://cdn.jsdelivr.net/sceditor/1.4.3/themes/default.min.css' => 'screen',
                                    'css/admin-styles.css' => 'screen');

            	Theme::$styles = array_merge($theme_css,$common_css);

	            Theme::$scripts['footer'] = array('http://code.jquery.com/jquery-1.10.2.min.js',
	            								  'js/oc-panel/sidebar.js',	
												  'js/jquery.sceditor.min.js',
												  'http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js', 
											      'http://cdn.jsdelivr.net/chosen/1.0.0/chosen.jquery.min.js',
                                                  'js/oc-panel/theme.init.js?v=2.1.6',
                                                  );
	        }

		}
		
		
	}


}
