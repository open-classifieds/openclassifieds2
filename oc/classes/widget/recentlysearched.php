<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Site stats widget
 *
 * @author      Oliver <oliver@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_RecentlySearched extends Widget
{

	public function __construct()
	{	
		$this->title = __('Recently searched');
		$this->description = __('Display previous user searches');

        $this->fields = array(  
                                'text_title'  => array( 'type'      => 'text',
                                                        'display'   => 'text',
                                                        'default'   => __('Recently searched'),
                                                        'label'     => __('Title displayed'),
                                                        'required'  => FALSE),
                                'max_items' => array(   'type'      => 'numeric',
                                                        'display'   => 'select',
                                                        'label'     => __('Number of searches to display'),
                                                        'options'   => array_combine(range(1,50),range(1,50)), 
                                                        'default'   => 5,
                                                        'required'  => TRUE),
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
        $this->before();

        //get the view file (check if exists in the theme if not default), and inject the widget
        $out = View::factory('widget/'.strtolower(get_class($this)),array('widget' => $this));

        $this->after();

        return $out;
    }

}
