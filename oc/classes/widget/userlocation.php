<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Site stats widget
 *
 * @author      Oliver <oliver@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_UserLocation extends Widget
{

	public function __construct()
	{	

		$this->title = __('User Location');
		$this->description = __('Display selected user location');

        $this->fields = array(  
                                'text_title'  => array( 'type'      => 'text',
                                                        'display'   => 'text',
                                                        'default'   => __('Your selected location'),
                                                        'label'     => __('Title displayed'),
                                                        'required'  => FALSE),
                                );
	}



    /**
     * get the title for the widget
     * @param string $title we will use it for the loaded widgets
     * @return string 
     */
    public function title($title = NULL)
    {
        return parent::title($this->text_title);
    }


    /**
     * Automatically executed before the widget action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {
        if (is_numeric($user_id_location = Cookie::get('user_location')))
        {
            $user_location = new Model_Location($user_id_location);

            if ($user_location->loaded())
            {
                $this->location = $user_location;
            }
        }
    }

    /**
     * renders the widget view with the data
     * @return string HTML 
     */     
    public function render()
    {
        //only if we have stored user location
        if ($this->loaded AND Cookie::get('user_location'))
        {
            $this->before();

            //get the view file (check if exists in the theme if not default), and inject the widget
            $out = View::factory('widget/'.strtolower(get_class($this)),array('widget' => $this));

            $this->after();

            return $out;
        }
        
        return FALSE;
    }

}