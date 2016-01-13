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
    }

    /**
     * Initialize properties before running the controller methods (actions),
     * so they are available to our action.
     */
    public function before($template = NULL)
    {
        parent::before();

        Theme::checker();
        
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
            $this->template->meta_copyright   = 'Open Classifieds '.Core::VERSION;
            $this->template->meta_copywrite   = $this->template->meta_copyright;//legacy for old themes
            $this->template->content          = '';
            $this->template->styles           = array();
            $this->template->scripts          = array();

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
                Theme::$styles = array_merge(Theme::$styles,array('css/jquery.cookiebar.css' => 'screen',));

                $this->template->scripts['footer'][] = 'js/jquery.cookiebar.js';
                $this->template->scripts['footer'][] = Route::url('default',array('controller'=>'jslocalization','action'=>'cookieconsent'));
            }

            //adblock detection
            if (Core::config('general.adblock')==1)
            {
                Theme::$styles = array_merge(Theme::$styles, array('css/adi.js/jquery.adi.css' => 'screen',));

                Theme::$scripts['footer'] [] = 'js/advertisement.js';
                Theme::$scripts['footer'] [] = 'js/jquery.adi.js';
                $this->template->scripts['footer'][] = Route::url('default',array('controller'=>'jslocalization','action'=>'adi'));
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
            if ($this->user!=FALSE  AND ($this->user->id_role==Model_Role::ROLE_ADMIN OR $this->user->id_role==Model_Role::ROLE_MODERATOR))
            {
                Alert::set(Alert::INFO, __('You are in maintenance mode, only you can see the website'), NULL, 'maintenance');
            }
            else
                $this->redirect(Route::url('maintenance'));
        }
    }    
        
        
    
}
