<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_FraudLabsPro extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('FraudLabsPro');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'fraudlabspro', Core::post('fraudlabspro'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'fraudlabspro']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/fraudlabspro', [
        ]);
    }
}
