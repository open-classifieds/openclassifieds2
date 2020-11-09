<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Search widget reader
 *
 * @author      Slobodan <slobodan@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Search extends Widget
{

	public function __construct()
	{

		$this->title 		= __('Search');
		$this->description 	= __('Advanced Search');

        $this->fields = array(	'text_title'    => array( 'type'      => 'text',
                                                        'display'   => 'text',
                                                        'default'   => __('Search'),
                                                        'label'     => __('Title displayed'),
                                                        'required'  => FALSE),

                                'advanced'      => array(  'type'      => 'text',
                                                        'display'   => 'select',
                                                        'label'     => __('Advanced option'),
                                                        'options'   => array('0'   => __('FALSE'),
                                                                             '1'   => __('TRUE'),
                                                                            ),
                                                        'default'   => 0,
                                                        'required'  => TRUE),

                                'custom'      => array(  'type'      => 'text',
                                                        'display'   => 'select',
                                                        'label'     => __('Custom fields in search'),
                                                        'options'   => array('0'   => __('FALSE'),
                                                                             '1'   => __('TRUE'),
                                                                            ),
                                                        'default'   => 0,
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
     * Automatically executed before the widget action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {
        // get all categories
        if ($this->advanced != FALSE)
        {

            $this->cat_items         = Model_Category::get_as_array(200);
            $this->cat_order_items   = Model_Category::get_multidimensional(200);
            $this->selected_category = NULL;

            if (core::request('category'))
            {
                $this->selected_category = core::request('category');
            }
            elseif (Model_Category::current()->loaded())
            {
                $this->selected_category = core::config('general.search_multi_catloc') ? array(Model_Category::current()->seoname) : Model_Category::current()->seoname;
            }

            // get all locations
            $this->loc_items         = Model_Location::get_as_array(100);
            $this->loc_order_items   = Model_Location::get_multidimensional(100);
            $this->selected_location = NULL;

            if (core::request('location'))
            {
                $this->selected_location = core::request('location');
            }
            elseif (Model_Location::current()->loaded())
            {
                $this->selected_location = core::config('general.search_multi_catloc') ? array(Model_Location::current()->seoname) : Model_Location::current()->seoname;
            }

        }

        if($this->custom != FALSE)
        {
            $fields = Model_Field::get_all();
            $this->custom_fields = $fields;
        }
    }


}
