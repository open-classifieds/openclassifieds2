<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_AutoDataGenerations extends Api_Controller {

    /**
     * Handle GET requests.
     */
    public function action_index()
    {
        try
        {
            if (is_numeric($this->request->param('id')))
            {
                return $this->action_get();
            }

            $generations = (New Model_AutoDataGeneration());

            //filter results by param, verify field exists and has a value
            $generations->api_filter($this->_filter_params);

            //how many? used in header X-Total-Count
            $count = $generations->count_all();

            //by default sort by published date
            if(empty($this->_sort))
            {
                $this->_sort['year'] = 'desc';
            }

            //after counting sort values
            $generations->api_sort($this->_sort);

            $generations = $generations->cached()->find_all();

            //as array
            foreach ($generations as $generation)
            {
                $output[] = $generation->as_array();
            }

            return $this->rest_output(['generations' => $output ?? []], 200, $count);
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
            if (! is_numeric($id_generation = $this->request->param('id')))
            {
                return $this->_error(__('Generation not found'), 404);
            }

            $generation = (new Model_AutoDataGeneration())
                ->where('id_generation','=', $id_generation)
                ->find();

            if (! $generation->loaded())
            {
                return $this->_error(__('Generation not found'), 404);
            }

            return $this->rest_output(['generation' => $generation->as_array()]);
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }

    }

} // END
