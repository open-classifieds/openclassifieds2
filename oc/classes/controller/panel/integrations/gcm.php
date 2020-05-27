<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_GCM extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Google Cloud Messaging');

        if($this->request->post())
        {
            Model_Config::set_value('general', 'gcm_apikey', Core::post('gcm_apikey'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'gcm']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/gcm', [
            'is_active' => (bool) Core::config('general.gcm_apikey')
        ]);
    }
}
