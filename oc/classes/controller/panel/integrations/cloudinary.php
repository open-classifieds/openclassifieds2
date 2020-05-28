<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Cloudinary extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Cloudinary');

        if($this->request->post())
        {
            Model_Config::set_value('advertisement', 'cloudinary_api_key', Core::post('cloudinary_api_key'));
            Model_Config::set_value('advertisement', 'cloudinary_api_secret', Core::post('cloudinary_api_secret'));
            Model_Config::set_value('advertisement', 'cloudinary_cloud_name', Core::post('cloudinary_cloud_name'));
            Model_Config::set_value('advertisement', 'cloudinary_cloud_preset', Core::post('cloudinary_cloud_preset'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'cloudinary']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/cloudinary', [
        ]);
    }
}
