<?php defined('SYSPATH') or die('No direct script access.');

class Api_User extends Api_Controller {


    public $user = FALSE;

    public function before()
    {
        parent::before();

        if (Core::extra_features() == FALSE)
            $this->_error('You need a Pro license to use the API',401);
        
        $key = isset($_SERVER['HTTP_USER_TOKEN'])?$_SERVER['HTTP__USER_TOKEN']:Core::request('user_token');

        //try authenticate the user
        if ($key == NULL OR ($this->user = Auth::instance()->api_login($key))==FALSE)
        {
            $this->_error(__('Wrong Api User Token'),401);
        }
    }



} // End api