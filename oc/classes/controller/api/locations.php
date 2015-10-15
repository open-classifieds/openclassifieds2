<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Locations extends Api_Controller {


    /**
     * Handle GET requests.
     */
    public function action_index()
    {
        try
        {
            if (is_numeric($this->request->param('id')))
            {
                $this->action_get();
            }
            else
            {
                $output = array();

                $locs = new Model_Location();

                //make a search with q? param
                if (isset($this->_params['q']) AND strlen($this->_params['q']))
                {
                    $locs->where_open()
                        ->where('name', 'like', '%'.$this->_params['q'].'%')
                        ->or_where('description', 'like', '%'.$this->_params['q'].'%')
                        ->where_close();
                }

                //filter results by param, verify field exists and has a value and sort the results
                $locs->api_filter($this->_filter_params)->api_sort($this->_sort);

                //how many? used in header X-Total-Count
                $count = $locs->count_all();


                $locs = $locs->cached()->find_all();

                //as array
                foreach ($locs as $location)
                {
                    $loc = $location->as_array();
                    //$loc['siblings'] = $location->get_siblings_ids();
                    $loc['icon']     = $location->get_icon();

                    $output[] = $loc;
                }

                $this->rest_output(array('locations' => $output),200,$count);
            }
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }

    /**
     * Handle GET requests.
     */
    public function action_all()
    {
        try
        {
            if (is_numeric($this->request->param('id')))
            {
                $this->action_get();
            }
            else
            {
                $this->rest_output(Model_Location::get_as_array());
            }
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }

    public function action_get()
    {
        try
        {
            if (is_numeric($id_location = $this->request->param('id')))
            {
                $location = new Model_location($id_location);
                if ($location->loaded())
                {
                    $loc = $location->as_array();
                    $loc['siblings'] = $location->get_siblings_ids();
                    $loc['icon']     = $location->get_icon();

                    $this->rest_output(array('location' => $loc));
                }
                else
                    $this->_error(__('Location not found'),404);
            }
            else
                $this->_error(__('Location not found'),404);
           
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
       
    }

} // END