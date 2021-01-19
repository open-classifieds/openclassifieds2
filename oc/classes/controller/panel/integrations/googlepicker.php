<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_GooglePicker extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Google Picker');

        if($this->request->post())
        {
            Model_Config::set_value('advertisement', 'picker_api_key', Core::post('picker_api_key'));
            Model_Config::set_value('advertisement', 'picker_client_id', Core::post('picker_client_id'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'googlepicker']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/google-picker', [
        ]);
    }
}
