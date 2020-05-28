<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Payfast extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Payfast');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'payfast_merchant_id', Core::post('payfast_merchant_id'));
            Model_Config::set_value('payment', 'payfast_merchant_key', Core::post('payfast_merchant_key'));
            Model_Config::set_value('payment', 'payfast_sandbox', Core::post('payfast_sandbox') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'payfast']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/payfast', [
        ]);
    }
}
