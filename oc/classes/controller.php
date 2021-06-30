<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Front end controller for OC app
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Controller extends Kohana_Controller
{
    public $template = 'main';

    /**
     * user if its loged in
     * @var Model_User
     */
    public $user = NULL;

    /**
     * @var  boolean  auto render template
     */
    public $auto_render = TRUE;

    /**
     * global category get from controller so we can access from anywhere like Controller::$category;
     * @var Model_Category DEPRECATED
     */
    public static $category = NULL;

    /**
     * global Location get from controller so we can access from anywhere like Controller::$location; DEPRECATED use Model_Category::current(); we keep it so still compatible with the themes.
     * @var Model_Location DEPRECATED
     */
    public static $location = NULL;

    /**
     * global image get from controller so we can access from anywhere like Controller::$image; used for facebook metas
     */
    public static $image = NULL;

    /**
     * global full width get from controller so we can access from anywhere like Controller::$full_width; used to render full width pages
     */
    public static $full_width = FALSE;


    /**
     *
     * Contruct that checks you are loged in before nothing else happens!
     */
    function __construct(Request $request, Response $response)
    {
        //setting the user
        $this->user = Auth::instance()->get_user();

        parent::__construct($request,$response);

        //check 2 step
        if ( strtolower($this->request->controller())!='auth' AND
            Auth::instance()->logged_in() AND
            core::config('general.google_authenticator')==TRUE AND
            Auth::instance()->get_user()->google_authenticator!='' AND
            Cookie::get('google_authenticator')!=Auth::instance()->get_user()->id_user )
        {
            //redirect to 2step page
            $url = Route::url('oc-panel',array('controller'=>'auth','action'=>'2step')).'?auth_redirect='.URL::current();
            $this->redirect($url);
        }

        //check 2 step SMS
        if ( strtolower($this->request->controller())!='auth' AND
            Auth::instance()->logged_in() AND
            core::config('general.sms_auth')==TRUE AND
            Cookie::get('sms_auth')!=Auth::instance()->get_user()->id_user AND
            Valid::phone($this->user->phone) )
        {
            //redirect to 2step sms page
            $url = Route::url('oc-panel',array('controller'=>'auth','action'=>'sms')).'?auth_redirect='.URL::current();
            $this->redirect($url);
        }

        //expired subscription
        if ($this->redirect_on_expired_subscription())
        {
            Alert::set(Alert::INFO, __('Please, choose a plan first'));
            HTTP::redirect(Route::url('pricing'));
        }
    }

    /**
     * Initialize properties before running the controller methods (actions),
     * so they are available to our action.
     */
    public function before($template = NULL)
    {
        parent::before();
        $this->private_site();
        $this->maintenance();

        /**
         * selected category
         */
        self::$category = Model_Category::current();

        /**
         * selected location
         */
        self::$location = Model_Location::current();

        //Gets a coupon if selected
        Model_Coupon::current();


        if($this->auto_render===TRUE)
        {
        	// Load the template
            if ($template!==NULL)
                $this->template= $template;
        	$this->template = View::factory($this->template);

            // Initialize template values
            $this->template->title            = core::config('general.site_name');
            $this->template->meta_keywords    = '';
            $this->template->meta_description = '';
            $this->template->meta_copyright   = 'Yclas '.Core::VERSION;
            $this->template->meta_copywrite   = $this->template->meta_copyright;//legacy for old themes
            $this->template->content          = '';
            $this->template->styles           = array();
            $this->template->scripts          = array();
            $this->template->amphtml          = NULL;

            $this->template->header  = View::factory('header');
            $this->template->footer  = View::factory('footer');


            // header_front_login fragment since CSRF gets cached :(
            // possible workaround ? @see http://kohanaframework.org/3.0/guide/kohana/fragments
            // if (Auth::instance()->logged_in())
            //     $this->template->header  = View::fragment('header_front_login','header');
            // else
            //     $this->template->header  = View::fragment('header_front','header');

            //$this->template->footer = View::fragment('footer_front','footer');
        }
    }

    /**
     * Fill in default values for our properties before rendering the output.
     */
    public function after()
    {
    	parent::after();

    	if ($this->auto_render === TRUE)
    	{

            // Add custom CSS if enabld and front controller
            if (is_subclass_of($this,'Auth_Controller')===FALSE AND ($custom_css = Theme::get_custom_css())!==FALSE )
                Theme::$styles = array_merge(Theme::$styles,array($custom_css => 'screen',));

            //cookie consent
            if (Core::config('general.cookie_consent')==1)
            {
                Theme::$styles = array_merge(Theme::$styles,array('//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css' => 'screen',));

                $this->template->scripts['footer'][] = '//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js';
                $this->template->scripts['footer'][] = Route::url('default',array('controller'=>'jslocalization','action'=>'cookieconsent'));
            }

            //adblock detection
            if (Core::config('general.adblock')==1)
            {
                Theme::$styles = array_merge(Theme::$styles, array('css/adi.js/jquery.adiadi.css' => 'screen',));

                Theme::$scripts['footer'] [] = 'js/jquery.adi.js';
                Theme::$scripts['footer'] [] = 'js/jquery.adiadi.js';
                $this->template->scripts['footer'][] = Route::url('default',array('controller'=>'jslocalization','action'=>'adi'));
            }

            //smartarget
            if (Core::config('general.smartarget_id') AND is_subclass_of($this,'Auth_Controller') === FALSE)
            {
                Theme::$scripts['footer'] [] = 'https://smartarget.online/loader.js?u=' . Core::config('general.smartarget_id') . '&source=yclas';
            }

    		// Add defaults to template variables.
    		$this->template->styles  = array_merge_recursive(Theme::$styles, $this->template->styles);
    		$this->template->scripts = array_reverse(array_merge_recursive(Theme::$scripts,$this->template->scripts));

            //in case theres no description given
            if ($this->template->meta_description == '')
                $this->template->meta_description = $this->template->title;

            //title concatenate the site name
            if ($this->template->title != '')
                $this->template->title .= ' - ';

    		$this->template->title .= core::config('general.site_name');

            //auto generate keywords and description from content
            seo::$charset = Kohana::$charset;

            $this->template->title = seo::text($this->template->title, 70);

            //not meta keywords given
            //remember keywords are useless :( http://googlewebmastercentral.blogspot.com/2009/09/google-does-not-use-keywords-meta-tag.html
    		if ($this->template->meta_keywords == '')
    		    $this->template->meta_keywords = seo::keywords($this->template->meta_description);

    		$this->template->meta_description = seo::text($this->template->meta_description);

    	}

        //no cache for logged users / actions, so we can use varnish or whatever ;)
        if ($this->user != FALSE)
            $this->response->headers('cache-control', 'no-cache, no-store, max-age=0, must-revalidate');

        //d($this->template);
    	$this->response->body($this->template->render());

    }

    /**
     * in case you set up general.maintenance to TRUE
     * @return void
     */
    public function maintenance()
    {
        //maintenance mode
        if (core::config('general.maintenance')==1 AND strtolower($this->request->controller())!='auth')
        {
            if ($this->user!=FALSE  AND ($this->user->is_admin() OR $this->user->is_moderator()))
            {
                Alert::set(Alert::INFO, __('You are in maintenance mode, only you can see the website'), NULL, 'maintenance');
            }
            else
                $this->redirect(Route::url('maintenance'));
        }
    }

    /**
     * in case you set up general.private_site to TRUE
     * @return void
     */
    public function private_site()
    {
        //private_site
        if (core::config('general.private_site')==1 AND $this->user==FALSE AND (strtolower($this->request->action()) != 'login') AND (strtolower($this->request->action()) != 'request') )
        {
            $this->auto_render = FALSE;
            $this->response->status(403);
            $this->template = View::factory('pages/error/403');
            $this->after();
            // Return the response
            die($this->response);
        }
    }

    private function redirect_on_expired_subscription()
    {
        if (! Core::extra_features())
        {
            return FALSE;
        }

        if (! Auth::instance()->logged_in())
        {
            return FALSE;
        }

        if (! $this->user->expired_subscription(TRUE))
        {
            return FALSE;
        }

        if (Core::config('general.subscriptions_expire_dont_limit_access'))
        {
            if (strtolower($this->request->controller()) == 'new')
            {
                return TRUE;
            }

            if (strtolower($this->request->controller()) == 'myads' AND strtolower($this->request->action()) == 'update')
            {
                return TRUE;
            }

            return FALSE;
        }

        if (strtolower($this->request->controller()) == 'plan')
        {
            return FALSE;
        }

        if (strtolower($this->request->controller()) == 'auth')
        {
            return FALSE;
        }

        if (strtolower($this->request->controller()) == 'stripecheckout')
        {
            return FALSE;
        }

        if (strtolower($this->request->controller()) == 'checkoutfree')
        {
            return FALSE;
        }

        if (strtolower($this->request->controller()) == 'pay')
        {
            return FALSE;
        }

        if (!(strtolower($this->request->controller()) == 'profile' AND strtolower($this->request->action()) == 'edit'))
        {
            return FALSE;
        }

        if (!(strtolower($this->request->controller()) == 'profile' AND strtolower($this->request->action()) == 'orders'))
        {
            return FALSE;
        }

        return TRUE;
    }

}
