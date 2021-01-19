<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Authorize extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Authorize');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'authorize_sandbox', Core::post('authorize_sandbox') ?? 0);
            Model_Config::set_value('payment', 'authorize_login', Core::post('authorize_login'));
            Model_Config::set_value('payment', 'authorize_key', Core::post('authorize_key'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'authorize']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/authorize', [
        ]);
    }
}
