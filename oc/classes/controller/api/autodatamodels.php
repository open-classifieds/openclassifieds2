<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_AutoDataModels extends Api_Controller {

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

            $models = (New Model_AutoDataModel());

            //filter results by param, verify field exists and has a value
            $models->api_filter($this->_filter_params);

            //how many? used in header X-Total-Count
            $count = $models->count_all();

            //by default sort by published date
            if(empty($this->_sort))
            {
                $this->_sort['name'] = 'asc';
            }

            //after counting sort values
            $models->api_sort($this->_sort);

            $models = $models->cached()->find_all();

            //as array
            foreach ($models as $model)
            {
                $output[] = $this->get_array($model);
            }

            return $this->rest_output(['models' => $output ?? []], 200, $count);
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
            if (! is_numeric($id_model = $this->request->param('id')))
            {
                return $this->_error(__('Model not found'), 404);
            }

            $model = (new Model_AutoDataModel())
                ->where('id_model','=', $id_model)
                ->find();

            if (! $model->loaded())
            {
                return $this->_error(__('Model not found'), 404);
            }

            return $this->rest_output(['model' => $this->get_array($model, TRUE)]);
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }

    }

    public static function get_array($model, $with_generations = FALSE)
    {
        $res = $model->as_array();

        if($with_generations)
        {
            $generations = (new Model_AutoDataGeneration())
                ->where('id_model','=', $model->id_model)
                ->find_all();

            foreach ($generations as $generation) {
                $res['generations'][] = [
                    'id_generation' => $generation->id_generation,
                    'name' => $generation->name,
                    'year' => $generation->year
                ];
            }
        }

        return $res;
    }

} // END
