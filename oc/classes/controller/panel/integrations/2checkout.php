<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_2Checkout extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('2Checkout');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'twocheckout_sandbox', Core::post('twocheckout_sandbox') ?? 0);
            Model_Config::set_value('payment', 'twocheckout_sid', Core::post('twocheckout_sid'));
            Model_Config::set_value('payment', 'twocheckout_secretword', Core::post('twocheckout_secretword'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => '2checkout']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/2checkout', [
        ]);
    }
}
