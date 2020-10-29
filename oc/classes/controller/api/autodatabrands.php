<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_AutoDataBrands extends Api_Controller {

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

            $brands = (New Model_AutoDataBrand());

            //filter results by param, verify field exists and has a value
            $brands->api_filter($this->_filter_params);

            //how many? used in header X-Total-Count
            $count = $brands->count_all();

            //by default sort by published date
            if(empty($this->_sort))
            {
                $this->_sort['name'] = 'asc';
            }

            //after counting sort values
            $brands->api_sort($this->_sort);

            $brands = $brands->cached()->find_all();

            //as array
            foreach ($brands as $brand)
            {
                $output[] = $this->get_array($brand);
            }

            return $this->rest_output(['brands' => $output ?? []], 200, $count);
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
            if (! is_numeric($id_brand = $this->request->param('id')))
            {
                return $this->_error(__('Brand not found'), 404);
            }

            $brand = (new Model_AutoDataBrand())
                ->where('id_brand','=', $id_brand)
                ->find();

            if (! $brand->loaded())
            {
                return $this->_error(__('Brand not found'), 404);
            }

            return $this->rest_output(['brand' => $this->get_array($brand, TRUE)]);
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }

    }


    public static function get_array($brand, $with_models = FALSE)
    {
        $res = $brand->as_array();

        if($with_models)
        {
            $models = (new Model_AutoDataModel())
                ->where('id_brand','=', $brand->id_brand)
                ->find_all();

            foreach ($models as $model) {
                $res['models'][] = ['id_model' => $model->id_model, 'name' => $model->name];
            }
        }

        return $res;
    }

} // END
