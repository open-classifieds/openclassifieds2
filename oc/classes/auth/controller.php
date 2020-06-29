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
				Alert::set(Alert::ERROR, sprintf(__('You do not have permissions to access %s'), $request->controller().' '.$request->action()));
				$url = Route::get('oc-panel')->uri(array(
													 'controller' => 'auth',
													 'action'     => 'login'));
				$this->redirect($url);
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
        if (Core::is_selfhosted() AND $this->user->is_admin())
        {
            Core::status();
        }

        $this->maintenance();

        $this->private_site();

		if($this->auto_render !== TRUE)
        {
            return;
        }

        // Load the template
        $this->template = $template === NULL ? 'oc-panel/layouts/master' : $template;

        $this->template = View::factory($this->template);

        // Initialize empty values
        $this->template->title            = __('Panel').' - '.core::config('general.site_name');
        $this->template->meta_keywords    = '';
        $this->template->meta_description = '';
        $this->template->header           = '';
        $this->template->content          = '';
        $this->template->footer           = '';
        $this->template->styles           = [];
        $this->template->scripts          = [];
        $this->template->user             = Auth::instance()->get_user();
        $this->template->panel_title      = NULL;

        if (Core::is_selfhosted())
        {
            $this->template->meta_copyright   = 'Yclas.com - '.date('Y');
        }
        else
        {
            $this->template->meta_copyright   = 'Yclas '.Core::VERSION;
        }

		//custom options for the theme
		Theme::$options = Theme::get_options();

		//we load earlier the theme since we need some info
		Theme::load();

        Theme::$styles = [];
        Theme::$scripts['footer'] = [];

        Form::$errors_tpl = View::factory('oc-panel/components/errors');
        Form::$error_tpl = View::factory('oc-panel/components/error');
        Alert::$tpl = View::factory('oc-panel/components/alert');
	}
}
