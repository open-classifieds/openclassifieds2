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
            $this->template->meta_copyright   = 'Open Classifieds '.Core::version;
            $this->template->content          = '';
            $this->template->styles           = array();
            $this->template->scripts          = array();

            //we can not cache this view since theres dynamic parts
            //$this->template->header  = View::factory('header');

            //setting inner views try to get from fragment
            // if (Auth::instance()->logged_in())
            //     $this->template->header  = View::fragment('header_front_login','header');
            // else
                $this->template->header  = View::factory('header');//             $this->template->header  = View::fragment('header_front','header');
                //no fragment since CSRF gets cached :(

            $this->template->footer = View::fragment('footer_front','footer');
            

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
    		// Add defaults to template variables.
    		$this->template->styles  = array_merge_recursive(Theme::$styles, $this->template->styles);
    		$this->template->scripts = array_reverse(array_merge_recursive(Theme::$scripts,$this->template->scripts));
    		
            if ($this->template->title!='')
                $concat = ' - ';
            else
                $concat = '';

    		$this->template->title.= $concat.core::config('general.site_name');

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
        if (core::config('general.maintenance')==1 AND $this->request->controller()!='auth')
        {
            $user = Auth::instance()->get_user();

            if ($user!==FALSE)
            {           
                if ($user->id_role==Model_Role::ROLE_ADMIN OR $user->id_role==Model_Role::ROLE_MODERATOR)
                    Alert::set(Alert::INFO, __('You are in maintenance mode, only you can see the website'));
                else
                    $this->request->redirect(Route::url('maintenance'));
            }
            else
                $this->request->redirect(Route::url('maintenance'));
        }
    }    
        
        
    
}
