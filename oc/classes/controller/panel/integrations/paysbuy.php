<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Paysbuy extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Paysbuy');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'paysbuy_sandbox', Core::post('paysbuy_sandbox') ?? 0);
            Model_Config::set_value('payment', 'paysbuy', Core::post('paysbuy'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'paysbuy']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/paysbuy', [
        ]);
    }
}
