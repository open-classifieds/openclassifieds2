<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Paymill extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Paymill');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'paymill_private', Core::post('paymill_private'));
            Model_Config::set_value('payment', 'paymill_public', Core::post('paymill_public'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'paymill']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/paymill', [
        ]);
    }
}
