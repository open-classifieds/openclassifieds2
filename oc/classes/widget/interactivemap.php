<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Disqus widget
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Interactivemap extends Widget
{

	public function __construct()
	{	

        $this->title = __('Interactive Map');
        $this->description = __('Display Interactive Map');

		$this->fields = array(	
                                'text_title'  => array( 'type'		=> 'text',
                                                        'display'	=> 'text',
                                                        'default'   => '',
                                                        'label'		=> __('Title displayed'),
                                                        'required'	=> FALSE),
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
     * renders the widget view with the data
     * @return string HTML 
     */     
    public function render()
    {
        //only on views disctict to home if map_active is TRUE
        if  (Core::extra_features() == TRUE 
                AND (!Core::Config('appearance.map_active') OR strtolower(Request::current()->controller()) != 'home'))
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