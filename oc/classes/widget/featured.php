<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Categories widget reader
 *
 * @author      Slobodan <slobodan@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Featured extends Widget
{

	public function __construct()
	{

		$this->title = __('Featured Ads');
		$this->description = __('Display Featured Ads');

		$this->fields = array(
						 		'featured_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Featured title displayed'),
						 		  						'default'   => __('Featured'),
														'required'	=> FALSE),

                                'ads_limit' => array(   'type'      => 'numeric',
                                                        'display'   => 'select',
                                                        'label'     => __('Number of featured ads to display'),
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
        return parent::title($this->featured_title);
    }

	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		$ads = new Model_Ad();
        $ads->where('status','=', Model_Ad::STATUS_PUBLISHED);

        $ads->where('featured','IS NOT', NULL)
        ->where('featured','>', Date::unix2mysql())
        ->order_by('featured','desc');

        $ads = $ads->limit($this->ads_limit)->find_all();

        $this->ads = $ads;

	}

    /**
     * renders the widget view with the data
     * @return string HTML
     */
    public function render()
    {
        $this->before();

        //only render if theres ads
        if (core::count($this->ads)>0)
        {
            //get the view file (check if exists in the theme if not default), and inject the widget
            $out = View::factory('widget/'.strtolower(get_class($this)),array('widget' => $this));

            $this->after();

            return $out;
        }

        return FALSE;
    }

}
