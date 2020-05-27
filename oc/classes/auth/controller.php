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
        if ($this->user->is_admin())
            Core::status();

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
        $this->template->meta_copyright   = 'Yclas '.Core::VERSION;
        $this->template->header           = '';
        $this->template->content          = '';
        $this->template->footer           = '';
        $this->template->styles           = [];
        $this->template->scripts          = [];
        $this->template->user             = Auth::instance()->get_user();
        $this->template->panel_title      = NULL;

		//custom options for the theme
		Theme::$options = Theme::get_options();

		//we load earlier the theme since we need some info
		Theme::load();

        Theme::$styles = [];
        Theme::$scripts['footer'] = [];

        Form::$errors_tpl = '<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a>
                        <p><strong>%s</strong></p>
                        <ul>%s</ul></div>';
        Form::$error_tpl    = '<div class="alert "><a class="close" data-dismiss="alert">×</a>%s</div>';
        Alert::$tpl = '
  <div x-data="{ show: true }" x-show="show" x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="mb-8">

<div class="rounded-md bg-green-50 p-4 %s">
  <div class="flex">
    <div class="flex-shrink-0">
      <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
      </svg>
    </div>
    <div class="ml-3">
      <h3 class="text-sm leading-5 font-medium text-green-800">
        %s
      </h3>
      <div class="mt-2 text-sm leading-5 text-green-700">
        <p>
          %s
        </p>
      </div>
    </div>
    <div class="ml-auto pl-3">
      <div class="-mx-1.5 -my-1.5">
        <button @click="show = false" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:bg-green-100 transition ease-in-out duration-150">
          <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</div>
</div>';
	}
}
