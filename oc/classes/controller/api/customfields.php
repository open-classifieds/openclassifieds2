<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Customfields extends Api_Controller {


    /**
     * Handle GET requests.
     */
    public function action_index()
    {
       $this->action_ads();
    }

    /**
     * Handle GET requests.
     */
    public function action_ads()
    {
        try
        {
            $fields = array();
            foreach (Model_Field::get_all() as $field => $values) 
            {
                $values['name'] = $field;
                $fields[] = $values;
            }
            $this->rest_output(array('fields' => $fields));
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }

     /**
     * Handle GET requests.
     */
    public function action_category()
    {
        try
        {
            if (is_numeric($this->request->param('id')))
            {
                $fields = array();
                foreach (Model_Field::get_by_category($this->request->param('id')) as $field => $values) 
                {
                    $values['name'] = $field;
                    $fields[] = $values;
                }
                $this->rest_output(array('fields' => $fields));
            }
            else
                $this->_error(__('Category not found'));
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }

    /**
     * Handle GET requests.
     */
    public function action_user()
    {
        try
        {
            $fields = array();
            foreach (Model_Userfield::get_all() as $field => $values) 
            {
                $values['name'] = $field;
                $fields[] = $values;
            }
            $this->rest_output(array('fields' => $fields));
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }



} // END