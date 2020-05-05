<?php defined('SYSPATH') or die('No direct script access.');

class Api_Auth extends Api_Controller {


    public function before()
    {
        parent::before();

        if (Core::extra_features() == FALSE)
            $this->_error('You need a Pro license to use the API',401);

        $key = isset($_SERVER['HTTP_APIKEY'])?$_SERVER['HTTP_APIKEY']:Core::request('apikey');

        //try normal api key not user
        if($key != Core::config('general.api_key'))
        {
            $this->_error(__('Wrong Api Key'),401);
        }        
    }



} // End api