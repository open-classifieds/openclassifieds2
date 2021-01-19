<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Payline extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Payline');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'payline_merchant_id', Core::post('payline_merchant_id'));
            Model_Config::set_value('payment', 'payline_access_key', Core::post('payline_access_key'));
            Model_Config::set_value('payment', 'payline_contract_number', Core::post('payline_contract_number'));
            Model_Config::set_value('payment', 'payline_testing', Core::post('payline_testing') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'payline']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/payline', [
        ]);
    }
}
